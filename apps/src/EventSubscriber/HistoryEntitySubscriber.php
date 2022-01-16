<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\HistoryService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HistoryEntitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected ParameterBagInterface $containerBag,
        protected EntityManagerInterface $entityManager,
        protected EnqueueMethod $enqueue
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [HistoryEntityEvent::class => 'onHistoryEntityEvent'];
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

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }
}
