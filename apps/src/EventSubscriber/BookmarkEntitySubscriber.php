<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\BookmarkEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookmarkEntitySubscriber implements EventSubscriberInterface
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [BookmarkEntityEvent::class => 'onBookmarkEntityEvent'];
    }

    public function onBookmarkEntityEvent(BookmarkEntityEvent $event): void
    {
        unset($event);
    }
}
