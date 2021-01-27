<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteUserRepository;
use Labstag\Service\GuardRouteService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GuardRouterSubscriber implements EventSubscriberInterface
{

    private TokenStorageInterface $token;

    private GuardRouteService $guardRouteService;

    private GroupeRepository $groupeRepository;

    public function __construct(
        TokenStorageInterface $token,
        GroupeRepository $groupeRepository,
        GuardRouteService $guardRouteService
    )
    {
        $this->groupeRepository  = $groupeRepository;
        $this->token             = $token;
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
                dd('ERROR 401');
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
            dd('ERROR 403');
        }
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }
}
