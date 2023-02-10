<?php

namespace Labstag\EventSubscriber;

use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GuardRouterSubscriber extends EventSubscriberLib
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return ['kernel.request' => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        $route = $request->attributes->get('_route');
        $token = $this->tokenStorage->getToken();
        $acces = $this->guardService->guardRoute($route, $token);
        if ($acces) {
            return;
        }

        $this->sessionService->flashBagAdd(
            'warning',
            $this->translator->trans('user.guard.nope')
        );

        throw new AccessDeniedException();
    }
}
