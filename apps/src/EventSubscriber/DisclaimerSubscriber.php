<?php

namespace Labstag\EventSubscriber;

use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DisclaimerSubscriber extends EventSubscriberLib
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
        $state = $this->disclaimerActivate($request);
        if (!$state) {
            return;
        }

        $requestEvent->setResponse(
            new RedirectResponse(
                $this->router->generate('disclaimer')
            )
        );
    }

    protected function disclaimerActivate(Request $request): bool
    {
        $config = $this->dataService->getConfig();
        $controller = $request->attributes->get('_controller');
        $key = 'disclaimer';
        $session = $request->getSession();
        if (!isset($config[$key]) || !isset($config[$key])) {
            return false;
        }

        if (0 === substr_count((string) $controller, 'Labstag')) {
            return false;
        }

        if (0 !== substr_count((string) $controller, 'Controller\\Api')) {
            return false;
        }

        if (0 !== substr_count((string) $controller, 'Controller\\Admin')) {
            return false;
        }

        if (0 !== substr_count((string) $controller, 'SecurityController')) {
            return false;
        }

        $disclaimer = $config[$key];
        $activate = (bool) $disclaimer['activate'];
        if (false === $activate) {
            return false;
        }

        return 1 !== $session->get($key, 0);
    }
}
