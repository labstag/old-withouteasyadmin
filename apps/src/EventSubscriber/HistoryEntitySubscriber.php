<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\HistoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HistoryEntitySubscriber implements EventSubscriberInterface
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
        return [HistoryEntityEvent::class => 'onHistoryEntityEvent'];
    }

    public function onHistoryEntityEvent(HistoryEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->enqueue->enqueue(
            HistoryService::class,
            'procress',
            [
                'historyId' => $entity->getId(),
            ]
        );
    }
}
