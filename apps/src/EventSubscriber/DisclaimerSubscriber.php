<?php

namespace Labstag\EventSubscriber;

use Labstag\Service\DataService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DisclaimerSubscriber implements EventSubscriberInterface
{

    protected DataService $dataService;

    protected RouterInterface $router;

    public function __construct(
        RouterInterface $router,
        DataService $dataService
    )
    {
        $this->dataService = $dataService;
        $this->router      = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $state   = $this->disclaimerActivate($request);
        if (!$state) {
            return;
        }

        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('disclaimer')
            )
        );
    }

    private function disclaimerActivate($request): bool
    {
        $config     = $this->dataService->getConfig();
        $controller = $request->attributes->get('_controller');
        $key        = 'disclaimer';
        $session    = $request->getSession();
        if (!isset($config[$key]) || !isset($config[$key][0])) {
            return false;
        }

        if (substr_count($controller, 'Labstag') == 0) {
            return false;
        }

        if (substr_count($controller, 'Controller\\Admin') != 0) {
            return false;
        }

        if (substr_count($controller, 'SecurityController') != 0) {
            return false;
        }

        $disclaimer = $config[$key][0];
        $activate   = $disclaimer['activate'];
        if (1 != $session->get($key, 0) && true == $activate) {
            return true;
        }

        return false;
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }
}
