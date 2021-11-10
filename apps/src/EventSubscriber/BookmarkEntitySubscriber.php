<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\BookmarkEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookmarkEntitySubscriber implements EventSubscriberInterface
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [BookmarkEntityEvent::class => 'onBookmarkEntityEvent'];
    }

    public function onBookmarkEntityEvent(BookmarkEntityEvent $event): void
    {
        unset($event);
    }
}
