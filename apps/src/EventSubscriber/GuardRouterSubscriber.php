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
use Symfony\Contracts\Translation\TranslatorInterface;

class GuardRouterSubscriber implements EventSubscriberInterface
{

    protected FlashBagInterface $flashbag;

    protected GroupeRepository $groupeRepository;

    protected GuardService $guardService;

    protected RequestStack $requestStack;

    protected RouterInterface $router;

    protected SessionInterface $session;

    protected TokenStorageInterface $token;

    protected TranslatorInterface $translator;

    public function __construct(
        RequestStack $requestStack,
        RouterInterface $router,
        TokenStorageInterface $token,
        GroupeRepository $groupeRepository,
        GuardService $guardService,
        TranslatorInterface $translator
    )
    {
        $this->translator       = $translator;
        $this->requestStack     = $requestStack;
        $this->groupeRepository = $groupeRepository;
        $this->token            = $token;
        $this->router           = $router;
        $this->guardService     = $guardService;
    }

    public static function getSubscribedEvents(): array
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
            $this->translator->trans('user.guard.nope')
        );

        throw new AccessDeniedException();
    }

    private function flashBagAdd(string $type, $message)
    {
        $requestStack = $this->requestStack;
        $request      = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }
}
