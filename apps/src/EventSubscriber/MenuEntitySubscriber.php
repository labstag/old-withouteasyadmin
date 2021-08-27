<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\EmailMenu;
use Labstag\Entity\Menu;
use Labstag\Event\MenuEntityEvent;
use Labstag\RequestHandler\EmailMenuRequestHandler;
use Labstag\Service\MenuMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\MenuPasswordHasherInterface;

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
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        if (isset($data['url']) && isset($data['route'])) {
            unset($data['route']);
        }

        if (isset($data['url']) && isset($data['param'])) {
            unset($data['param']);
        }

        $menu->setData($data);

        $this->entityManager->persist($menu);
        $this->entityManager->flush();
    }
}
