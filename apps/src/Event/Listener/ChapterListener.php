<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Meta;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Entity\Render;
use Labstag\Interfaces\PublicInterface;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\HistoryService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ChapterListener implements EventSubscriberInterface
{
    public function __construct(
        protected EnqueueMethod $enqueueMethod,
        protected ParameterBagInterface $parameterBag,
        protected LoggerInterface $logger
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('persist', $lifecycleEventArgs);
    }

    public function postRemove(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('remove', $lifecycleEventArgs);
    }

    public function postUpdate(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('update', $lifecycleEventArgs);
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof Chapter) {
            return;
        }

        $this->logger->info($action.' '.get_class($object));
        $this->verifMetas($object);
        /** @var History $history */
        $history = $object->getHistory();
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

    private function verifMetas(
        PublicInterface $public
    ): void
    {
        $title = null;
        $metas = $public->getMetas();
        if (0 != count($metas)) {
            return;
        }

        $meta   = new Meta();
        $method = '';
        $title  = '';
        $this->verifMetasChapter($public, $method, $title);
        $this->verifMetasEdito($public, $method, $title);
        $this->verifMetasHistory($public, $method, $title);
        $this->verifMetasPage($public, $method, $title);
        $this->verifMetasPost($public, $method, $title);
        $this->verifMetasRender($public, $method, $title);
        if ('' != $method) {
            /** @var callable $callable */
            $callable = [
                $meta,
                $method,
            ];
            call_user_func($callable, $public);
        }

        $meta->setTitle($title);
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
