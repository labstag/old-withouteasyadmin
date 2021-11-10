<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\ChapterEntityEvent;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\HistoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChapterEntitySubscriber implements EventSubscriberInterface
{

    protected EnqueueMethod $enqueue;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        EnqueueMethod $enqueue
    )
    {
        $this->enqueue       = $enqueue;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [ChapterEntityEvent::class => 'onChapterEntityEvent'];
    }

    public function onChapterEntityEvent(ChapterEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->enqueue->enqueue(
            HistoryService::class,
            'procress',
            [
                'historyId' => $entity->getRefhistory()->getId(),
            ]
        );
    }
}
