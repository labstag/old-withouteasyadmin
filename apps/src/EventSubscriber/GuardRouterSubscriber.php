<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class GuardRouterSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        dump($event);
        dump('bb');
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }
}
