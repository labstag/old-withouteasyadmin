<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Event\PageEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PageEntitySubscriber implements EventSubscriberInterface
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [PageEntityEvent::class => 'onPageEntityEvent'];
    }

    public function onPageEntityEvent(PageEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $slug   = $entity->getSlug();
        if (!is_null($entity->getParent())) {
            $slug = $entity->getParent()->getFrontSlug().'/'.$entity->getSlug();
        }

        $entity->setFrontslug((string) $slug);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
