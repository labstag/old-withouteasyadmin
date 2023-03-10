<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\Route as EntityRoute;
use Labstag\Entity\RouteGroupe;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteUserRepository;
use Labstag\RequestHandler\RouteGroupeRequestHandler;
use Labstag\RequestHandler\RouteUserRequestHandler;
use Labstag\Service\GuardService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route(path: '/api/guard/route')]
class GuardRouteController extends ApiControllerLib
{
    #[Route(path: '/group/{group}', name: 'api_guard_routegroup', methods: ['POST'])]
    public function group(
        RouteGroupeRepository $routeGroupeRepository,
        Groupe $groupe,
        GuardService $guardService,
        RouteGroupeRequestHandler $routeGroupeRequestHandler,
        Request $request
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state  = $request->request->get('state');
        $routes = $guardService->getGuardRoutesForGroupe($groupe);
        /** @var EntityRoute $route */
        foreach ($routes as $route) {
            $data = $this->setRouteGroupe(
                $routeGroupeRepository,
                $guardService,
                $data,
                $groupe,
                $route,
                (bool) $state,
                $routeGroupeRequestHandler
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/groups/{route}', name: 'api_guard_routegroups', methods: ['POST'])]
    public function groups(
        RouteGroupeRepository $routeGroupeRepository,
        GuardService $guardService,
        EntityRoute $entityRoute,
        RouteGroupeRequestHandler $routeGroupeRequestHandler,
        Request $request,
        GroupeRepository $groupeRepository
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state   = (bool) $request->request->get('state');
        $groupes = $groupeRepository->findAll();
        foreach ($groupes as $groupe) {
            /** @var Groupe $groupe */
            $data = $this->setRouteGroupe(
                $routeGroupeRepository,
                $guardService,
                $data,
                $groupe,
                $entityRoute,
                $state,
                $routeGroupeRequestHandler
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/', name: 'api_guard_route')]
    public function index(Request $request): JsonResponse
    {
        $data = [
            'group' => [],
        ];
        $get     = $request->query->all();
        $data    = $this->getGuardRouteOrWorkflow($data, $get, RouteUser::class);
        $results = $this->getResultWorkflow($request, RouteGroupe::class);
        foreach ($results as $result) {
            /** @var RouteGroupe $result */
            /** @var Groupe $groupe */
            $groupe = $result->getRefgroupe();
            /** @var EntityRoute $route */
            $route           = $result->getRefroute();
            $data['group'][] = [
                'groupe' => $groupe->getCode(),
                'route'  => $route->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/setgroup/{group}/{route}', name: 'api_guard_routesetgroup', methods: ['POST'])]
    public function setgroup(
        RouteGroupeRepository $routeGroupeRepository,
        GuardService $guardService,
        Groupe $groupe,
        EntityRoute $entityRoute,
        Request $request,
        RouteGroupeRequestHandler $routeGroupeRequestHandler
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = (bool) $request->request->get('state');
        $data  = $this->setRouteGroupe(
            $routeGroupeRepository,
            $guardService,
            $data,
            $groupe,
            $entityRoute,
            $state,
            $routeGroupeRequestHandler
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{user}/{route}', name: 'api_guard_routesetuser', methods: ['POST'])]
    public function setuser(
        RouteUserRepository $routeUserRepository,
        GuardService $guardService,
        User $user,
        EntityRoute $entityRoute,
        Request $request,
        RouteUserRequestHandler $routeUserRequestHandler
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = (bool) $request->request->get('state');
        $data  = $this->setRouteUser(
            $routeUserRepository,
            $guardService,
            $data,
            $user,
            $state,
            $entityRoute,
            $routeUserRequestHandler
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/user/{user}', name: 'api_guard_routeuser', methods: ['POST'])]
    public function user(
        RouteUserRepository $routeUserRepository,
        GuardService $guardService,
        User $user,
        Request $request,
        RouteUserRequestHandler $routeUserRequestHandler
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state  = (bool) $request->request->get('state');
        $routes = $guardService->getGuardRoutesForUser($user);
        /** @var EntityRoute $route */
        foreach ($routes as $route) {
            /** @var EntityRoute $route */
            $data = $this->setRouteUser(
                $routeUserRepository,
                $guardService,
                $data,
                $user,
                $state,
                $route,
                $routeUserRequestHandler
            );
        }

        return new JsonResponse($data);
    }

    private function setRouteGroupe(
        RouteGroupeRepository $routeGroupeRepository,
        GuardService $guardService,
        array $data,
        ?Groupe $groupe,
        ?EntityRoute $entityRoute,
        bool $state,
        RouteGroupeRequestHandler $routeGroupeRequestHandler
    ): array
    {
        $routeGroupe = $routeGroupeRepository->findOneBy(
            [
                'refgroupe' => $groupe,
                'refroute'  => $entityRoute,
            ]
        );
        if (false === $state) {
            if ($routeGroupe instanceof RouteGroupe) {
                $data['delete'] = 1;
                $routeGroupeRepository->remove($routeGroupe);
            }

            return $data;
        }

        /** @var Groupe $groupe */
        /** @var EntityRoute $entityRoute */
        $enable = $guardService->guardRouteEnableGroupe($entityRoute, $groupe);
        if ('superadmin' === $groupe->getCode() || !$enable) {
            return $data;
        }

        if (!$routeGroupe instanceof RouteGroupe) {
            $routeGroupe = new RouteGroupe();
            $data['add'] = 1;
            $routeGroupe->setRefgroupe($groupe);
            $routeGroupe->setRefroute($entityRoute);
            $old = clone $routeGroupe;
            $routeGroupe->setState($state);
            $routeGroupeRequestHandler->handle($old, $routeGroupe);
        }

        return $data;
    }

    private function setRouteUser(
        RouteUserRepository $routeUserRepository,
        guardService $guardService,
        array $data,
        ?UserInterface $user,
        bool $state,
        EntityRoute $entityRoute,
        RouteUserRequestHandler $routeUserRequestHandler
    ): array
    {
        $routeUser = $routeUserRepository->findOneBy(['refuser' => $user, 'refroute' => $entityRoute]);
        if (false == $state) {
            if ($routeUser instanceof RouteUser) {
                $data['delete'] = 1;
                $routeUserRepository->remove($routeUser);
            }

            return $data;
        }

        /** @var User $user */
        /** @var Groupe $groupe */
        $groupe = $user->getRefgroupe();
        $enable = $guardService->guardRouteEnableGroupe($entityRoute, $groupe);
        if ('superadmin' === $groupe->getCode() || !$enable) {
            return $data;
        }

        if (!$routeUser instanceof RouteUser) {
            $data['add'] = 1;
            $routeUser   = new RouteUser();
            $routeUser->setRefuser($user);
            $routeUser->setRefroute($entityRoute);
            $old = clone $routeUser;
            $routeUser->setState($state);
            $routeUserRequestHandler->handle($old, $routeUser);
        }

        return $data;
    }
}
