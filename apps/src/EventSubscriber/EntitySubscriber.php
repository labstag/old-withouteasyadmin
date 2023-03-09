<?php

namespace Labstag\EventSubscriber;

use Exception;
use Labstag\Entity\Chapter;
use Labstag\Entity\Configuration;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\History;
use Labstag\Entity\Meta;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Entity\Render;
use Labstag\Entity\User;
use Labstag\Event\AttachmentEntityEvent;
use Labstag\Event\BlockEntityEvent;
use Labstag\Event\BookmarkEntityEvent;
use Labstag\Event\ChapterEntityEvent;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Event\EditoEntityEvent;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Event\MenuEntityEvent;
use Labstag\Event\PageEntityEvent;
use Labstag\Event\ParagraphEntityEvent;
use Labstag\Event\PostEntityEvent;
use Labstag\Event\RenderEntityEvent;
use Labstag\Event\UserEntityEvent;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Service\HistoryService;

class EntitySubscriber extends EventSubscriberLib
{
    public static function getSubscribedEvents(): array
    {
        return [
            AttachmentEntityEvent::class    => 'onAttachmentEntityEvent',
            BlockEntityEvent::class         => 'onBlockEntityEvent',
            BookmarkEntityEvent::class      => 'onBookmarkEntityEvent',
            EditoEntityEvent::class         => 'onEditoEntityEvent',
            ChapterEntityEvent::class       => 'onChapterEntityEvent',
            ConfigurationEntityEvent::class => 'onConfigurationEntityEvent',
            HistoryEntityEvent::class       => 'onHistoryEntityEvent',
            MenuEntityEvent::class          => 'onMenuEntityEvent',
            PageEntityEvent::class          => 'onPageEntityEvent',
            ParagraphEntityEvent::class     => 'onParagraphEntityEvent',
            PostEntityEvent::class          => 'onPostEntityEvent',
            RenderEntityEvent::class        => 'onRenderEntityEvent',
            UserEntityEvent::class          => 'onUserEntityEvent',
        ];
    }

    public function onAttachmentEntityEvent(AttachmentEntityEvent $attachmentEntityEvent): void
    {
        unset($attachmentEntityEvent);
    }

    public function onBlockEntityEvent(BlockEntityEvent $blockEntityEvent): void
    {
        $block       = $blockEntityEvent->getNewEntity();
        $classentity = $this->blockService->getTypeEntity($block);
        if (is_null($classentity)) {
            $this->entityManager->remove($block);
            $this->entityManager->flush();

            return;
        }

        $entity = $this->blockService->getEntity($block);
        if (!is_null($entity)) {
            return;
        }

        /** @var BlockInterface $entity */
        $entity = new $classentity();
        $entity->setBlock($block);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function onBookmarkEntityEvent(BookmarkEntityEvent $bookmarkEntityEvent): void
    {
        unset($bookmarkEntityEvent);
    }

    public function onChapterEntityEvent(ChapterEntityEvent $chapterEntityEvent): void
    {
        $chapter = $chapterEntityEvent->getNewEntity();
        $this->verifMetas($chapter);
        $this->enqueueMethod->enqueue(
            HistoryService::class,
            'process',
            [
                'fileDirectory' => $this->parameterBag->get('file_directory'),
                'historyId'     => $chapter->getRefhistory()->getId(),
                'all'           => false,
            ]
        );
    }

    public function onConfigurationEntityEvent(ConfigurationEntityEvent $configurationEntityEvent): void
    {
        $this->cache->delete('configuration');
        $post = $configurationEntityEvent->getPost();
        $this->setRobotsTxt($post);
        $this->flushPostConfiguration($post);
    }

    public function onEditoEntityEvent(EditoEntityEvent $editoEntityEvent): void
    {
        $edito = $editoEntityEvent->getNewEntity();
        $this->verifMetas($edito);
    }

    public function onHistoryEntityEvent(HistoryEntityEvent $historyEntityEvent): void
    {
        $history = $historyEntityEvent->getNewEntity();
        $this->verifMetas($history);
        $this->enqueueMethod->enqueue(
            HistoryService::class,
            'process',
            [
                'fileDirectory' => $this->parameterBag->get('file_directory'),
                'historyId'     => $history->getId(),
                'all'           => false,
            ]
        );
    }

    public function onMenuEntityEvent(MenuEntityEvent $menuEntityEvent): void
    {
        $menu = $menuEntityEvent->getNewEntity();
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
            $data['params'] = json_decode(
                json: (string) $data['param'],
                depth: 512,
                flags: JSON_THROW_ON_ERROR
            );
            unset($data['param']);
        }

        if (isset($data['url'], $data['route'])) {
            unset($data['route']);
        }

        if (isset($data['url'], $data['param'])) {
            unset($data['param']);
        }

        $menu->setData($data);
        $this->menuRepository->add($menu);
    }

    public function onPageEntityEvent(PageEntityEvent $pageEntityEvent): void
    {
        $entity = $pageEntityEvent->getNewEntity();
        $this->verifMetas($entity);
        $page = $entity->getParent();
        if (!is_null($page)) {
            return;
        }

        $entity->setSlug('');
        $this->pageRepository->add($entity);
    }

    public function onParagraphEntityEvent(ParagraphEntityEvent $paragraphEntityEvent): void
    {
        $this->onParagraphEntityEventInit($paragraphEntityEvent);
        $this->onParagraphEntityEventData($paragraphEntityEvent);
    }

    public function onPostEntityEvent(PostEntityEvent $postEntityEvent): void
    {
        $post = $postEntityEvent->getNewEntity();
        $this->verifMetas($post);
    }

    public function onRenderEntityEvent(RenderEntityEvent $renderEntityEvent): void
    {
        $render = $renderEntityEvent->getNewEntity();
        $this->verifMetas($render);
    }

    public function onUserEntityEvent(UserEntityEvent $userEntityEvent): void
    {
        $user      = $userEntityEvent->getOldEntity();
        $newEntity = $userEntityEvent->getNewEntity();
        $this->setPassword($newEntity);
        $this->setPrincipalMail($user, $newEntity);
        $this->setChangePassword($user, $newEntity);
        $this->setDeletedAt($user, $newEntity);
    }

    protected function flushPostConfiguration(array $post): void
    {
        foreach ($post as $key => $value) {
            if ('_token' == $key) {
                continue;
            }

            $configuration = $this->configurationRepository->findOneBy(['name' => $key]);
            if (!$configuration instanceof Configuration) {
                $configuration = new Configuration();
                $configuration->setName($key);
            }

            $metatags = (array) $this->parameterBag->get('metatags');
            if (in_array($key, $metatags)) {
                $value = $value[0];
            }

            $configuration->setValue($value);
            $this->configurationRepository->add($configuration);
        }

        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('param.change')
        );
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
        foreach ($states as $state) {
            foreach ($state as $entity) {
                $entity->setDeletedAt($datetime);
                /** @var ServiceEntityRepositoryLib $repository */
                $repository = $this->repositoryService->get($entity::class);
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

        $encodePassword = $this->userPasswordHasher->hashPassword(
            $user,
            $plainPassword
        );

        $user->setPassword($encodePassword);
        if ('valider' == $user->getState()) {
            $this->userMailService->changePassword($user);
        }

        $this->userRepository->add($user);
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
            /** @var EmailUser $emailUser */
            $emailUser->setPrincipal(false);
            if ($emailUser->getAddress() === $address) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }

            /** @var ServiceEntityRepositoryLib $repository */
            $repository = $this->repositoryService->get($emailUser::class);
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

        $this->emailUserRequestHandler->handle($old, $emailUser);
        $this->emailUserRequestHandler->changeWorkflowState($emailUser, ['submit', 'valider']);
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

    private function onParagraphEntityEventData(ParagraphEntityEvent $paragraphEntityEvent): void
    {
        $paragraph = $paragraphEntityEvent->getNewEntity();
        $this->paragraphService->setData($paragraph);
    }

    private function onParagraphEntityEventInit(ParagraphEntityEvent $paragraphEntityEvent): void
    {
        $paragraph = $paragraphEntityEvent->getNewEntity();
        $oldEntity = $paragraphEntityEvent->getOldEntity();
        if (0 != $oldEntity->getPosition()) {
            return;
        }

        $classentity = $this->paragraphService->getTypeEntity($paragraph);
        if (is_null($classentity)) {
            $this->entityManager->remove($paragraph);
            $this->entityManager->flush();

            return;
        }

        $entity = $this->paragraphService->getEntity($paragraph);
        if (!is_null($entity)) {
            return;
        }

        /** @var ParagraphInterface $entity */
        $entity = new $classentity();
        $entity->setParagraph($paragraph);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    private function verifMetas(
        mixed $entity
    ): void
    {
        $title = null;
        $metas = $entity->getMetas();
        if (0 != count($metas)) {
            return;
        }

        $meta   = new Meta();
        $method = '';
        $title  = '';
        $this->verifMetasChapter($entity, $method, $title);
        $this->verifMetasEdito($entity, $method, $title);
        $this->verifMetasHistory($entity, $method, $title);
        $this->verifMetasPage($entity, $method, $title);
        $this->verifMetasPost($entity, $method, $title);
        $this->verifMetasRender($entity, $method, $title);
        if ('' != $method) {
            /** @var callable $callable */
            $callable = [
                $meta,
                $method,
            ];
            call_user_func($callable, $entity);
        }

        $meta->setTitle($title);
        $this->entityManager->persist($meta);
        $this->entityManager->flush();
    }

    private function verifMetasChapter(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof Chapter) {
            return;
        }

        $method = 'setChapter';
        $title  = $entity->getName();
    }

    private function verifMetasEdito(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof Edito) {
            return;
        }

        $method = 'setEdito';
        $title  = $entity->getTitle();
    }

    private function verifMetasHistory(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof History) {
            return;
        }

        $method = 'setHistory';
        $title  = $entity->getName();
    }

    private function verifMetasPage(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof Page) {
            return;
        }

        $method = 'setPage';
        $title  = $entity->getName();
    }

    private function verifMetasPost(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof Post) {
            return;
        }

        $method = 'setPost';
        $title  = $entity->getTitle();
    }

    private function verifMetasRender(
        mixed $entity,
        string &$method,
        string &$title
    ): void
    {
        if (!$entity instanceof Render) {
            return;
        }

        $method = 'setRender';
        $title  = $entity->getName();
    }
}
