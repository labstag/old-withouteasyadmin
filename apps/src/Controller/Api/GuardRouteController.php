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
        $state = $request->request->get('state');
        $routes = $guardService->getGuardRoutesForGroupe($groupe);
        // @var EntityRoute $route
        foreach ($routes as $route) {
            $data = $this->setRouteGroupe(
                $routeGroupeRepository,
                $guardService,
                $data,
                $groupe,
                $route,
                $state,
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
        $state = $request->request->get('state');
        $groupes = $groupeRepository->findAll();
        foreach ($groupes as $groupe) {
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
        $get = $request->query->all();
        $data = $this->getGuardRouteOrWorkflow($data, $get, RouteUser::class);
        $results = $this->getResultWorkflow($request, RouteGroupe::class);
        foreach ($results as $result) {
            // @var RouteGroupe $row
            $data['group'][] = [
                'groupe' => $result->getRefgroupe()->getCode(),
                'route'  => $result->getRefroute()->getName(),
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
        $state = $request->request->get('state');
        $data = $this->setRouteGroupe(
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
        $state = $request->request->get('state');
        $data = $this->setRouteUser(
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
        $state = $request->request->get('state');
        $routes = $guardService->getGuardRoutesForUser($user);
        // @var EntityRoute $route
        foreach ($routes as $route) {
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
        $data,
        $group,
        $route,
        $state,
        $routeGroupeRH
    )
    {
        $routeGroupe = $routeGroupeRepository->findOneBy(
            [
                'refgroupe' => $group,
                'refroute'  => $route,
            ]
        );
        if ('0' === $state) {
            if ($routeGroupe instanceof RouteGroupe) {
                $data['delete'] = 1;
                $routeGroupeRepository->remove($routeGroupe);
            }

            return $data;
        }

        $enable = $guardService->guardRouteEnableGroupe($route->getName(), $group);
        if ('superadmin' === $group->getCode() || !$enable) {
            return $data;
        }

        if (!$routeGroupe instanceof RouteGroupe) {
            $routeGroupe = new RouteGroupe();
            $data['add'] = 1;
            $routeGroupe->setRefgroupe($group);
            $routeGroupe->setRefroute($route);
            $old = clone $routeGroupe;
            $routeGroupe->setState($state);
            $routeGroupeRH->handle($old, $routeGroupe);
        }

        return $data;
    }

    /**
     * @return mixed[]
     */
    private function setRouteUser(
        RouteUserRepository $routeUserRepository,
        guardService $guardService,
        array $data,
        $user,
        $state,
        EntityRoute $entityRoute,
        RouteUserRequestHandler $routeUserRequestHandler
    ): array
    {
        $routeUser = $routeUserRepository->findOneBy(['refuser' => $user, 'refroute' => $entityRoute]);
        if ('0' === $state) {
            if ($routeUser instanceof RouteUser) {
                $data['delete'] = 1;
                $routeUserRepository->remove($routeUser);
            }

            return $data;
        }

        $enable = $guardService->guardRouteEnableGroupe($entityRoute, $user->getRefgroupe());
        if ('superadmin' === $user->getRefgroupe()->getCode() || !$enable) {
            return $data;
        }

        if (!$routeUser instanceof RouteUser) {
            $data['add'] = 1;
            $routeUser = new RouteUser();
            $routeUser->setRefuser($user);
            $routeUser->setRefroute($entityRoute);
            $old = clone $routeUser;
            $routeUser->setState($state);
            $routeUserRequestHandler->handle($old, $routeUser);
        }

        return $data;
    }
}
