<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\Menu;
use Psr\Log\LoggerInterface;

class MenuListener implements EventSubscriberInterface
{
    public function __construct(
        protected LoggerInterface $logger
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('persist', $lifecycleEventArgs);
    }

    public function postRemove(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('remove', $lifecycleEventArgs);
    }

    public function postUpdate(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('update', $lifecycleEventArgs);
    }

    private function execute(Menu $menu): void
    {
        $dataMenu = $menu->getData();
        if (!is_array($dataMenu)) {
            return;
        }

        foreach ($dataMenu as $key => $value) {
            if (!is_null($value)) {
                continue;
            }

            unset($dataMenu[$key]);
        }

        if (isset($dataMenu['param'], $dataMenu['route'])) {
            $dataMenu['params'] = json_decode(
                json: (string) $dataMenu['param'],
                depth: 512,
                flags: JSON_THROW_ON_ERROR
            );
        }

        if (isset($dataMenu['url'], $dataMenu['route'])) {
            unset($dataMenu['route']);
        }

        if (isset($dataMenu['url'], $dataMenu['param'])) {
            unset($dataMenu['param']);
        }

        $menu->setData($dataMenu);
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof Menu) {
            return;
        }

        $this->logger->info($action.' '.get_class($object));
        $this->execute($object);
    }
}
