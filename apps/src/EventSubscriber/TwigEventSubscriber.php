<?php

namespace Labstag\EventSubscriber;

use Labstag\Service\AdminBoutonService;
use Labstag\Service\DataService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    protected DataService $dataService;

    protected Environment $twig;

    protected AdminBoutonService $adminBoutonService;


    public function __construct(
        Environment $twig,
        AdminBoutonService $adminBoutonService,
        DataService $dataService
    )
    {
        $this->adminBoutonService = $adminBoutonService;
        $this->twig               = $twig;
        $this->dataService        = $dataService;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->setLoginPage($event);
        $this->setAdminPages($event);
        $this->twig->addGlobal(
            'config',
            $this->dataService->getConfig()
        );
    }

    private function setAdminPages(ControllerEvent $event): void
    {
        $controller = $event->getRequest()->attributes->get('_controller');
        if (substr_count($controller, 'Controller\Admin') == 0) {
            return;
        }

        $this->twig->addGlobal(
            'btnadmin',
            $this->adminBoutonService->get()
        );
    }

    private function setLoginPage(ControllerEvent $event): void
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');
        $routes       = [
            'app_login',
            'admin_profil',
        ];

        if (!in_array($currentRoute, $routes)) {
            return;
        }

        $this->twig->addGlobal(
            'oauthActivated',
            $this->dataService->getOauthActivated()
        );
    }

    public static function getSubscribedEvents()
    {
        return [ControllerEvent::class => 'onControllerEvent'];
    }
}
