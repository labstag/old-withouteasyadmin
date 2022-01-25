<?php

namespace Labstag\EventSubscriber;

use Exception;
use Labstag\Entity\Configuration;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Menu;
use Labstag\Entity\User;
use Labstag\Event\AttachmentEntityEvent;
use Labstag\Event\BookmarkEntityEvent;
use Labstag\Event\ChapterEntityEvent;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Event\MenuEntityEvent;
use Labstag\Event\PageEntityEvent;
use Labstag\Event\UserEntityEvent;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Service\HistoryService;

class EntitySubscriber extends EventSubscriberLib
{

    public static function getSubscribedEvents(): array
    {
        return [
            AttachmentEntityEvent::class    => 'onAttachmentEntityEvent',
            ConfigurationEntityEvent::class => 'onConfigurationEntityEvent',
            BookmarkEntityEvent::class      => 'onBookmarkEntityEvent',
            ChapterEntityEvent::class       => 'onChapterEntityEvent',
            HistoryEntityEvent::class       => 'onHistoryEntityEvent',
            MenuEntityEvent::class          => 'onMenuEntityEvent',
            PageEntityEvent::class          => 'onPageEntityEvent',
            UserEntityEvent::class          => 'onUserEntityEvent',
        ];
    }

    public function onAttachmentEntityEvent(AttachmentEntityEvent $event): void
    {
        unset($event);
    }

    public function onBookmarkEntityEvent(BookmarkEntityEvent $event): void
    {
        unset($event);
    }

    public function onChapterEntityEvent(ChapterEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->enqueue->enqueue(
            HistoryService::class,
            'process',
            [
                'fileDirectory' => $this->getParameter('file_directory'),
                'historyId'     => $entity->getRefhistory()->getId(),
                'all'           => false,
            ]
        );
    }

    public function onConfigurationEntityEvent(ConfigurationEntityEvent $event): void
    {
        $this->cache->delete('configuration');
        $post = $event->getPost();
        $this->setRobotsTxt($post);
        $this->flushPostConfiguration($post);
    }

    public function onHistoryEntityEvent(HistoryEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->enqueue->enqueue(
            HistoryService::class,
            'process',
            [
                'fileDirectory' => $this->getParameter('file_directory'),
                'historyId'     => $entity->getId(),
                'all'           => false,
            ]
        );
    }

    public function onMenuEntityEvent(MenuEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->setDataMenu($entity);
    }

    public function onPageEntityEvent(PageEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $slug   = $entity->getSlug();
        $parent = $entity->getParent();
        if (!is_null($parent)) {
            $frontSlug = $parent->getFrontSlug();

            $slug = ('' == $frontSlug) ? $entity->getSlug() : ($frontSlug.'/'.$entity->getSlug());
        }

        $entity->setFrontslug((string) $slug);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function onUserEntityEvent(UserEntityEvent $event): void
    {
        $oldEntity = $event->getOldEntity();
        $newEntity = $event->getNewEntity();
        $this->setPassword($newEntity);
        $this->setPrincipalMail($oldEntity, $newEntity);
        $this->setChangePassword($oldEntity, $newEntity);
        $this->setDeletedAt($oldEntity, $newEntity);
    }

    protected function flushPostConfiguration(array $post): void
    {
        foreach ($post as $key => $value) {
            if ('_token' == $key) {
                continue;
            }

            $configuration = $this->getRepository(Configuration::class)->findOneBy(['name' => $key]);
            if (!$configuration instanceof Configuration) {
                $configuration = new Configuration();
                $configuration->setName($key);
            }

            if (in_array($key, $this->getParameter('metatags'))) {
                $value = $value[0];
            }

            $configuration->setValue($value);
            $this->entityManager->persist($configuration);
        }

        $this->entityManager->flush();
        $this->flashBagAdd(
            'success',
            $this->translator->trans('param.change')
        );
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function setChangePassword(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getState() == $newEntity->getState()) {
            return;
        }

        if ('lostpassword' != $oldEntity->getState()) {
            return;
        }

        $this->userMailService->changePassword($newEntity);
        $this->flashBagAdd(
            'success',
            'Changement de mot de passe effectué'
        );
    }

    protected function setDataMenu(Menu $menu): void
    {
        $data = $menu->getData();
        if (0 == count((array) $data)) {
            return;
        }

        $data = $data[0];
        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                continue;
            }

            unset($data[$key]);
        }

        if (isset($data['url'], $data['route'])) {
            unset($data['route']);
        }

        if (isset($data['url'], $data['param'])) {
            unset($data['param']);
        }

        $menu->setData($data);

        $this->entityManager->persist($menu);
        $this->entityManager->flush();
    }

    protected function setDeletedAt(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getDeletedAt() == $newEntity->getDeletedAt()) {
            return;
        }

        $states = [
            'addressUsers' => $newEntity->getAddressUsers(),
            'bookmarks'    => $newEntity->getBookmarks(),
            'editos'       => $newEntity->getEditos(),
            'emailUsers'   => $newEntity->getEmailUsers(),
            'linkUsers'    => $newEntity->getLinkUsers(),
            'noteInternes' => $newEntity->getMemos(),
            'phoneUsers'   => $newEntity->getPhoneUsers(),
            'posts'        => $newEntity->getPosts(),
        ];

        $datetime = $newEntity->getDeletedAt();
        foreach ($states as $data) {
            foreach ($data as $entity) {
                $entity->setDeletedAt($datetime);
                $this->entityManager->persist($entity);
            }

            $this->entityManager->flush();
        }
    }

    protected function setPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if ('' === $plainPassword || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->passwordEncoder->hashPassword(
            $user,
            $plainPassword
        );

        $user->setPassword($encodePassword);
        if ('valider' == $user->getState()) {
            $this->userMailService->changePassword($user);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.password.change')
        );
    }

    protected function setPrincipalMail(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getEmail() == $newEntity->getEmail()) {
            return;
        }

        $address = $newEntity->getEmail();
        $emails  = $newEntity->getEmailUsers();
        $trouver = false;
        foreach ($emails as $emailUser) {
            // @var EmailUser $emailUser
            $emailUser->setPrincipal(false);
            if ($emailUser->getAddress() === $address) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }

            $this->entityManager->persist($emailUser);
        }

        if ('valider' == $newEntity->getState()) {
            $this->userMailService->changeEmailPrincipal($newEntity);
        }

        $this->entityManager->flush();
        $this->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.emailprincal.change')
        );

        if ($trouver) {
            return;
        }

        $emailUser = new EmailUser();
        $old       = clone $emailUser;
        $emailUser->setRefuser($newEntity);
        $emailUser->setPrincipal(true);
        $emailUser->setAddress($address);
        $this->emailUserRH->handle($old, $emailUser);
        $this->emailUserRH->changeWorkflowState($emailUser, ['submit', 'valider']);
    }

    protected function setRobotsTxt(array $post): void
    {
        if (!isset($post['robotstxt'])) {
            return;
        }

        try {
            $value = $post['robotstxt'];
            $file  = 'robots.txt';
            if (is_file($file)) {
                unlink($file);
            }

            file_put_contents($file, $value);
            $msg = $this->translator->trans('admin.robotstxt.file', ['%file%' => $file]);
            $this->logger->info($msg);
            $this->flashBagAdd('success', $msg);
        } catch (Exception $exception) {
            $errorMsg = sprintf(
                'Exception : Erreur %s dans %s L.%s : %s',
                $exception->getCode(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getMessage()
            );
            $this->logger->error($errorMsg);
            $this->flashBagAdd('danger', $errorMsg);
        }
    }
}
