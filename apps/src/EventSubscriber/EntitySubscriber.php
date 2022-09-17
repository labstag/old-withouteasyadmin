<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Entity\Configuration;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Menu;
use Labstag\Entity\User;
use Labstag\Event\AttachmentEntityEvent;
use Labstag\Event\BlockEntityEvent;
use Labstag\Event\BookmarkEntityEvent;
use Labstag\Event\ChapterEntityEvent;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Event\MenuEntityEvent;
use Labstag\Event\PageEntityEvent;
use Labstag\Event\ParagraphEntityEvent;
use Labstag\Event\UserEntityEvent;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Queue\EnqueueMethod;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\MenuRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\BlockService;
use Labstag\Service\HistoryService;
use Labstag\Service\ParagraphService;
use Labstag\Service\SessionService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EntitySubscriber extends EventSubscriberLib
{
    public function __construct(
        protected ParameterBagInterface $containerBag,
        protected EnqueueMethod $enqueue,
        protected EntityManagerInterface $entityManager,
        protected ParagraphService $paragraphService,
        protected BlockService $blockService,
        protected UserPasswordHasherInterface $passwordEncoder,
        protected SessionService $sessionService,
        protected EmailUserRequestHandler $emailUserRH,
        protected TranslatorInterface $translator,
        protected ConfigurationRepository $configurationRepo,
        protected PageRepository $pageRepo,
        protected MenuRepository $menuRepo,
        protected UserRepository $userRepo
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AttachmentEntityEvent::class    => 'onAttachmentEntityEvent',
            ParagraphEntityEvent::class     => 'onParagraphEntityEvent',
            BlockEntityEvent::class         => 'onBlockEntityEvent',
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

    public function onBlockEntityEvent(BlockEntityEvent $event)
    {
        $newEntity   = $event->getNewEntity();
        $classentity = $this->blockService->getTypeEntity($newEntity);
        if (is_null($classentity)) {
            $this->entityManager->remove($newEntity);
            $this->entityManager->flush();

            return;
        }

        $entity = $this->blockService->getEntity($newEntity);
        if (!is_null($entity)) {
            return;
        }

        $entity = new $classentity();
        $entity->setBlock($newEntity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
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
        $parent = $entity->getParent();
        if (!is_null($parent)) {
            return;
        }

        $entity->setSlug('');
        $this->pageRepo->add($entity);
    }

    public function onParagraphEntityEvent(ParagraphEntityEvent $event)
    {
        $newEntity = $event->getNewEntity();
        $oldEntity = $event->getOldEntity();
        if (0 != $oldEntity->getPosition()) {
            return;
        }

        $classentity = $this->paragraphService->getTypeEntity($newEntity);
        if (is_null($classentity)) {
            $this->entityManager->remove($newEntity);
            $this->entityManager->flush();

            return;
        }

        $entity = $this->paragraphService->getEntity($newEntity);
        if (!is_null($entity)) {
            return;
        }

        $entity = new $classentity();
        $entity->setParagraph($newEntity);

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

            $configuration = $this->configurationRepo->findOneBy(['name' => $key]);
            if (!$configuration instanceof Configuration) {
                $configuration = new Configuration();
                $configuration->setName($key);
            }

            if (in_array($key, $this->getParameter('metatags'))) {
                $value = $value[0];
            }

            $configuration->setValue($value);
            $this->configurationRepo->add($configuration);
        }

        $this->sessionService->flashBagAdd(
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
        $this->sessionService->flashBagAdd(
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

        if (isset($data['param'], $data['route'])) {
            $data['params'] = json_decode((string) $data['param'], null, 512, JSON_THROW_ON_ERROR);
            unset($data['param']);
        }

        if (isset($data['url'], $data['route'])) {
            unset($data['route']);
        }

        if (isset($data['url'], $data['param'])) {
            unset($data['param']);
        }

        $menu->setData($data);
        $this->menuRepo->add($menu);
    }

    protected function setDeletedAt(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getDeletedAt() === $newEntity->getDeletedAt()) {
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
                $repository = $this->getRepository($entity::class);
                $repository->add($entity);
            }
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

        $this->userRepo->add($user);
        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.password.change')
        );
    }

    protected function setPrincipalMail(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getEmail() === $newEntity->getEmail()) {
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

            $repository = $this->getRepository($emailUser::class);
            $repository->add($emailUser);
        }

        if ('valider' == $newEntity->getState()) {
            $this->userMailService->changeEmailPrincipal($newEntity);
        }

        $this->entityManager->flush();
        $this->sessionService->flashBagAdd(
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
            $this->sessionService->flashBagAdd('success', $msg);
        } catch (Exception $exception) {
            $this->errorService->set($exception);
        }
    }
}
