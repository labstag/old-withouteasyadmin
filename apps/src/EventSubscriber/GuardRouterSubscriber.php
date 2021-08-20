<?php

namespace Labstag\EventSubscriber;

use Labstag\Repository\GroupeRepository;
use Labstag\Service\GuardService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GuardRouterSubscriber implements EventSubscriberInterface
{

    protected FlashBagInterface $flashbag;

    protected GroupeRepository $groupeRepository;

    protected GuardService $guardService;

    protected RouterInterface $router;

    protected SessionInterface $session;

    protected TokenStorageInterface $token;

    protected RequestStack $requestStack;

    public function __construct(
        RequestStack $requestStack,
        RouterInterface $router,
        TokenStorageInterface $token,
        GroupeRepository $groupeRepository,
        GuardService $guardService
    )
    {
        $this->requestStack = $requestStack;
        $request            = $requestStack->getCurrentRequest();
        if (!is_null($request)) {
            $session        = $requestStack->getSession();
            $this->flashbag = $session->getFlashBag();
        }

        $this->groupeRepository = $groupeRepository;
        $this->token            = $token;
        $this->router           = $router;
        $this->guardService     = $guardService;
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $route   = $request->attributes->get('_route');
        $token   = $this->token->getToken();
        $acces   = $this->guardService->guardRoute($route, $token);
        if ($acces) {
            return;
        }

        $this->flashBagAdd(
            'warning',
            "Vous n'avez pas les droits nécessaires"
        );
        throw new AccessDeniedException();
    }

    private function flashBagAdd(string $type, $message)
    {
        if (!isset($this->flashbag) || !$this->flashbag instanceof FlashBagInterface) {
            return;
        }

        $this->flashbag->add($type, $message);
    }
}
