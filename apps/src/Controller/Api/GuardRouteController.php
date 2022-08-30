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
        RouteGroupeRepository $repository,
        Groupe $group,
        GuardService $guardService,
        RouteGroupeRequestHandler $routeGroupeRH,
        Request $request
    )
    {
        $data   = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state  = $request->request->get('state');
        $routes = $guardService->getGuardRoutesForGroupe($group);
        // @var EntityRoute $route
        foreach ($routes as $route) {
            $data = $this->setRouteGroupe(
                $repository,
                $guardService,
                $data,
                $group,
                $route,
                $state,
                $routeGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/groups/{route}', name: 'api_guard_routegroups', methods: ['POST'])]
    public function groups(
        RouteGroupeRepository $routeRepo,
        GuardService $guardService,
        EntityRoute $route,
        RouteGroupeRequestHandler $routeGroupeRH,
        Request $request,
        GroupeRepository $groupeRepo
    )
    {
        $data    = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state   = $request->request->get('state');
        $groupes = $groupeRepo->findAll();
        foreach ($groupes as $group) {
            $data = $this->setRouteGroupe(
                $routeRepo,
                $guardService,
                $data,
                $group,
                $route,
                $state,
                $routeGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/', name: 'api_guard_route')]
    public function index(Request $request)
    {
        $data    = [
            'group' => [],
        ];
        $get     = $request->query->all();
        $data    = $this->getGuardRouteOrWorkflow($data, $get, RouteUser::class);
        $results = $this->getResultWorkflow($request, RouteGroupe::class);
        foreach ($results as $row) {
            // @var RouteGroupe $row
            $data['group'][] = [
                'groupe' => $row->getRefgroupe()->getCode(),
                'route'  => $row->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/setgroup/{group}/{route}', name: 'api_guard_routesetgroup', methods: ['POST'])]
    public function setgroup(
        RouteGroupeRepository $repository,
        GuardService $guardService,
        Groupe $group,
        EntityRoute $route,
        Request $request,
        RouteGroupeRequestHandler $routeGroupeRH
    )
    {
        $data  = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->get('state');
        $data  = $this->setRouteGroupe(
            $repository,
            $guardService,
            $data,
            $group,
            $route,
            $state,
            $routeGroupeRH
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{user}/{route}', name: 'api_guard_routesetuser', methods: ['POST'])]
    public function setuser(
        RouteUserRepository $repository,
        GuardService $guardService,
        User $user,
        EntityRoute $route,
        Request $request,
        RouteUserRequestHandler $routeUserRH
    )
    {
        $data  = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->get('state');
        $data  = $this->setRouteUser(
            $repository,
            $guardService,
            $data,
            $user,
            $state,
            $route,
            $routeUserRH
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/user/{user}', name: 'api_guard_routeuser', methods: ['POST'])]
    public function user(
        RouteUserRepository $repository,
        GuardService $guardService,
        User $user,
        Request $request,
        RouteUserRequestHandler $routeUserRH
    )
    {
        $data   = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state  = $request->request->get('state');
        $routes = $guardService->getGuardRoutesForUser($user);
        // @var EntityRoute $route
        foreach ($routes as $route) {
            $data = $this->setRouteUser(
                $repository,
                $guardService,
                $data,
                $user,
                $state,
                $route,
                $routeUserRH
            );
        }

        return new JsonResponse($data);
    }

    private function setRouteGroupe(
        RouteGroupeRepository $repository,
        GuardService $guardService,
        $data,
        $group,
        $route,
        $state,
        $routeGroupeRH
    )
    {
        $routeGroupe = $repository->findOneBy(
            [
                'refgroupe' => $group,
                'refroute'  => $route,
            ]
        );
        if ('0' === $state) {
            if ($routeGroupe instanceof RouteGroupe) {
                $data['delete'] = 1;
                $repository->remove($routeGroupe);
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

    private function setRouteUser(
        RouteUserRepository $repository,
        guardService $guardService,
        array $data,
        $user,
        $state,
        EntityRoute $route,
        RouteUserRequestHandler $routeUserRH
    )
    {
        $routeUser = $repository->findOneBy(['refuser' => $user, 'refroute' => $route]);
        if ('0' === $state) {
            if ($routeUser instanceof RouteUser) {
                $data['delete'] = 1;
                $repository->remove($routeUser);
            }

            return $data;
        }

        $enable = $guardService->guardRouteEnableGroupe($route->getName(), $user->getRefgroupe());
        if ('superadmin' === $user->getRefgroupe()->getCode() || !$enable) {
            return $data;
        }

        if (!$routeUser instanceof RouteUser) {
            $data['add'] = 1;
            $routeUser   = new RouteUser();
            $routeUser->setRefuser($user);
            $routeUser->setRefroute($route);
            $old = clone $routeUser;
            $routeUser->setState($state);
            $routeUserRH->handle($old, $routeUser);
        }

        return $data;
    }
}
