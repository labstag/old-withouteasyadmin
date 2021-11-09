<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\ChapterEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChapterEntitySubscriber implements EventSubscriberInterface
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [ChapterEntityEvent::class => 'onChapterEntityEvent'];
    }

    public function onChapterEntityEvent(ChapterEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
    }
}
