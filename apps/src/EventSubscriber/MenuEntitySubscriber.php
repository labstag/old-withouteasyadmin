<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Menu;
use Labstag\Event\MenuEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MenuEntitySubscriber implements EventSubscriberInterface
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [MenuEntityEvent::class => 'onMenuEntityEvent'];
    }

    public function onMenuEntityEvent(MenuEntityEvent $event): void
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
