<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteUserRepository;
use Labstag\Service\GuardRouteService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GuardRouterSubscriber implements EventSubscriberInterface
{

    protected TokenStorageInterface $token;

    protected SessionInterface $session;

    protected GuardRouteService $guardRouteService;

    protected GroupeRepository $groupeRepository;

    protected RouterInterface $router;

    public function __construct(
        SessionInterface $session,
        RouterInterface $router,
        TokenStorageInterface $token,
        GroupeRepository $groupeRepository,
        GuardRouteService $guardRouteService
    )
    {
        $this->groupeRepository  = $groupeRepository;
        $this->session           = $session;
        $this->token             = $token;
        $this->router            = $router;
        $this->guardRouteService = $guardRouteService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $route   = $request->attributes->get('_route');
        $all     = $this->guardRouteService->all();
        $token   = $this->token->getToken();
        if (!array_key_exists($route, $all)) {
            return;
        }

        if (empty($token) || !$token->getUser() instanceof User) {
            $groupe = $this->groupeRepository->findOneBy(['code' => 'visiteur']);
            if (!$this->guardRouteService->searchRouteGroupe($groupe, $route)) {
                /** @var Session $session */
                $session = $this->session;
                $session->getFlashBag()->add(
                    'note',
                    "Vous n'avez pas les droits nécessaires"
                );
                $event->setResponse(
                    new RedirectResponse(
                        $this->router->generate('login')
                    )
                );
            }

            return;
        }

        /** @var User $user */
        $user   = $token->getUser();
        $groupe = $user->getGroupe();
        if ('superadmin' == $groupe->getCode()) {
            return;
        }

        $state = $this->guardRouteService->searchRouteUser($user, $route);
        if (!$state) {
            throw new AccessDeniedException();
        }
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }
}
