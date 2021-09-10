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
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\RouteGroupeRequestHandler;
use Labstag\RequestHandler\RouteUserRequestHandler;
use Labstag\Service\GuardService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/guard/route")
 */
class GuardRouteController extends ApiControllerLib
{
    /**
     * @Route("/group/{group}", name="api_guard_routegroup", methods={"POST"})
     */
    public function group(
        Groupe $group,
        GuardService $guardService,
        RouteGroupeRepository $routeGroupeRepo,
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
        /** @var EntityRoute $route */
        foreach ($routes as $route) {
            $data = $this->setRouteGroupe(
                $guardService,
                $data,
                $routeGroupeRepo,
                $group,
                $route,
                $state,
                $routeGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/groups/{route}", name="api_guard_routegroups", methods={"POST"})
     */
    public function groups(
        GuardService $guardService,
        EntityRoute $route,
        GroupeRepository $groupeRepo,
        RouteGroupeRepository $routeGroupeRepo,
        RouteGroupeRequestHandler $routeGroupeRH,
        Request $request
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
                $guardService,
                $data,
                $routeGroupeRepo,
                $group,
                $route,
                $state,
                $routeGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/", name="api_guard_route")
     */
    public function index(
        RouteGroupeRepository $routeGroupeRepo,
        RouteUserRepository $routeUserRepo,
        UserRepository $userRepository,
        Request $request
    )
    {
        $data = [
            'group' => [],
        ];
        $get  = $request->query->all();
        if (array_key_exists('user', $get)) {
            $data['user'] = [];
            $user         = $userRepository->find($get['user']);
            if (!$user instanceof User) {
                return new JsonResponse($data);
            }

            $results = $routeUserRepo->findEnable($user);
            foreach ($results as $row) {
                /** @var RouteUser $row */
                $data['user'][] = [
                    'route' => $row->getRefroute()->getName(),
                ];
            }
        }

        $results = $this->getResultWorkflow($request, $userRepository, $routeGroupeRepo);

        foreach ($results as $row) {
            /** @var RouteGroupe $row */
            $data['group'][] = [
                'groupe' => $row->getRefgroupe()->getCode(),
                'route'  => $row->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/setgroup/{group}/{route}", name="api_guard_routesetgroup", methods={"POST"})
     */
    public function setgroup(
        GuardService $guardService,
        Groupe $group,
        EntityRoute $route,
        Request $request,
        RouteGroupeRepository $routeGroupeRepo,
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
            $guardService,
            $data,
            $routeGroupeRepo,
            $group,
            $route,
            $state,
            $routeGroupeRH
        );

        return new JsonResponse($data);
    }

    /**
     * @Route("/setuser/{user}/{route}", name="api_guard_routesetuser", methods={"POST"})
     */
    public function setuser(
        GuardService $guardService,
        User $user,
        EntityRoute $route,
        Request $request,
        RouteUserRepository $routeUserRepo,
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
            $guardService,
            $data,
            $routeUserRepo,
            $user,
            $state,
            $route,
            $routeUserRH
        );

        return new JsonResponse($data);
    }

    /**
     * @Route("/user/{user}", name="api_guard_routeuser", methods={"POST"})
     */
    public function user(
        GuardService $guardService,
        User $user,
        Request $request,
        RouteUserRepository $routeUserRepo,
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
        /** @var EntityRoute $route */
        foreach ($routes as $route) {
            $data = $this->setRouteUser(
                $guardService,
                $data,
                $routeUserRepo,
                $user,
                $state,
                $route,
                $routeUserRH
            );
        }

        return new JsonResponse($data);
    }

    private function getResultWorkflow($request, $userRepository, $routeGroupeRepo)
    {
        $get = $request->query->all();
        if (array_key_exists('user', $get)) {
            $user = $userRepository->find($get['user']);

            return $routeGroupeRepo->findEnable($user->getRefgroupe());
        }

        return $routeGroupeRepo->findEnable();
    }

    private function setRouteGroupe(
        GuardService $guardService,
        $data,
        $routeGroupeRepo,
        $group,
        $route,
        $state,
        $routeGroupeRH
    )
    {
        $routeGroupe = $routeGroupeRepo->findOneBy(['refgroupe' => $group, 'refroute' => $route]);
        if ('0' === $state) {
            if ($routeGroupe instanceof RouteGroupe) {
                $data['delete'] = 1;
                $this->entityManager->remove($routeGroupe);
                $this->entityManager->flush();
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
        guardService $guardService,
        array $data,
        RouteUserRepository $routeUserRepo,
        $user,
        $state,
        EntityRoute $route,
        RouteUserRequestHandler $routeUserRH
    )
    {
        $routeUser = $routeUserRepo->findOneBy(['refuser' => $user, 'refroute' => $route]);
        if ('0' === $state) {
            if ($routeUser instanceof RouteUser) {
                $data['delete'] = 1;
                $this->entityManager->remove($routeUser);
                $this->entityManager->flush();
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
