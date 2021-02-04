<?php

namespace Labstag\EventSubscriber;

use Labstag\Service\DataService;
use Labstag\Singleton\BreadcrumbsSingleton;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    protected DataService $dataService;

    protected Environment $twig;

    protected RouterInterface $router;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(
        RouterInterface $router,
        Environment $twig,
        CsrfTokenManagerInterface $csrfTokenManager,
        DataService $dataService
    )
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router           = $router;
        $this->twig             = $twig;
        $this->dataService      = $dataService;
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

    protected function setAdminPages(ControllerEvent $event): void
    {
        $controller = $event->getRequest()->attributes->get('_controller');
        if (0 == substr_count($controller, 'Controller\Admin')) {
            return;
        }

        $this->setBreadCrumbsAdmin();
    }

    protected function setBreadCrumbsAdmin()
    {
        $adminBreadcrumbs = [
            'Home' => $this->router->generate('admin'),
        ];

        BreadcrumbsSingleton::getInstance()->add($adminBreadcrumbs);
    }

    protected function setLoginPage(ControllerEvent $event): void
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
