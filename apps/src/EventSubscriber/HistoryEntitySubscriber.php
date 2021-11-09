<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Menu;
use Labstag\Event\HistoryEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HistoryEntitySubscriber implements EventSubscriberInterface
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [HistoryEntityEvent::class => 'onHistoryEntityEvent'];
    }

    public function onHistoryEntityEvent(HistoryEntityEvent $event): void
    {
        $entity = $event->getNewEntity();
        $this->setData($entity);
    }

    protected function setData(Menu $menu): void
    {
        $data = $menu->getData();
        if (0 == count($data)) {
            return;
        }

        $data = $data[0];
        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                continue;
            }

            unset($data[$key]);
        }

        if (isset($data['url'], $data['route'])) {
            unset($data['route']);
        }

        if (isset($data['url'], $data['param'])) {
            unset($data['param']);
        }

        $menu->setData($data);

        $this->entityManager->persist($menu);
        $this->entityManager->flush();
    }
}
