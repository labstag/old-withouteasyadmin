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
use Labstag\Repository\RouteRepository;
use Labstag\Repository\RouteUserRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/guard', name: 'api_guard_')]
class GuardController extends ApiControllerLib
{
    #[Route(path: '/groups/{groupe}', name: 'group')]
    public function groupe(
        RouteGroupeRepository $routeGroupeRepository,
        Groupe $groupe
    ): JsonResponse
    {
        return $this->getRefgroupe($routeGroupeRepository, $groupe);
    }

    #[Route(path: '/groups', name: 'groups')]
    public function groupes(RouteGroupeRepository $routeGroupeRepository): JsonResponse
    {
        return $this->getRefgroupe($routeGroupeRepository);
    }

    #[Route(path: '/setgroup/{route}/{groupe}', name: 'setgroup')]
    public function setgroup(
        string $route,
        string $groupe,
        Request $request,
        GroupeRepository $groupeRepository,
        RouteRepository $routeRepository,
        RouteGroupeRepository $routeGroupeRepository
    ): JsonResponse
    {
        $post   = $request->request->all();
        $data   = ['ok' => false];
        $groupe = $groupeRepository->findOneBy(['code' => $groupe]);
        $route  = $routeRepository->findOneBy(['name' => $route]);
        if (empty($groupe) || empty($route) || !array_key_exists('_token', $post) || !is_string($post['_token'])) {
            $data['error'] = 'Erreur de saisie';

            return new JsonResponse($data);
        }

        /** @var Groupe $groupe */
        $groupeId = $groupe->getId();
        /** @var EntityRoute $route */
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-'.$groupeId.'-route-'.$routeId,
            (string) $post['_token']
        );
        if ($this->csrfTokenManager->isTokenValid($csrfToken)) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $routeGroupe = $routeGroupeRepository->findOneBy(
            [
                'refgroupe' => $groupe,
                'refroute'  => $route,
            ]
        );
        if (!$routeGroupe instanceof RouteGroupe) {
            $routeGroupe = new RouteGroupe();
            $routeGroupe->setRefGroupe($groupe);
            $routeGroupe->setRefRoute($route);
        }

        $routeGroupe->setState((bool) $post['state']);
        $routeGroupeRepository->save($routeGroupe);
        $data['ok'] = true;

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{route}/{user}', name: 'setuser', methods: ['POST'])]
    public function setuser(
        string $route,
        string $user,
        Request $request,
        UserRepository $userRepository,
        RouteRepository $routeRepository,
        RouteUserRepository $routeUserRepository
    ): JsonResponse
    {
        $data  = ['ok' => false];
        $post  = $request->request->all();
        $user  = $userRepository->findOneBy(['username' => $user]);
        $route = $routeRepository->findOneBy(['name' => $route]);
        if (empty($user) || empty($route) || !array_key_exists('_token', $post) || !is_string($post['_token'])) {
            $data['error'] = 'Erreur de saisie';

            return new JsonResponse($data);
        }

        /** @var User $user */
        $userId = $user->getId();
        /** @var EntityRoute $route */
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-'.$userId.'-route-'.$routeId,
            (string) $post['_token']
        );
        if ($this->csrfTokenManager->isTokenValid($csrfToken)) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $routeUser = $routeUserRepository->findOneBy(
            [
                'refuser'  => $user,
                'refroute' => $route,
            ]
        );
        if (!$routeUser instanceof RouteUser) {
            $routeUser = new RouteUser();
            $routeUser->setRefuser($user);
            $routeUser->setRefRoute($route);
        }

        $routeUser->setState((bool) $post['state']);
        $userRepository->save($routeUser);
        $data['ok'] = true;

        return new JsonResponse($data);
    }

    #[Route(path: '/users/{user}', name: 'user')]
    public function user(
        User $user,
        RouteGroupeRepository $routeGroupeRepository,
        RouteUserRepository $routeUserRepository
    ): JsonResponse
    {
        $data = [
            'groups' => [],
            'user'   => [],
        ];
        $results = $routeGroupeRepository->findEnableByGroupe($user->getRefgroupe());
        if (!is_iterable($results)) {
            return new JsonResponse($data);
        }

        foreach ($results as $row) {
            /** @var RouteGroupe $row */
            /** @var EntityRoute $route */
            $route            = $row->getRefroute();
            $data['groups'][] = [
                'route' => $route->getName(),
            ];
        }

        $results = $routeUserRepository->findEnableByUser($user);
        if (!is_iterable($results)) {
            return new JsonResponse($data);
        }

        foreach ($results as $result) {
            /** @var RouteUser $row */
            $data['user'][] = [
                'route' => $result->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    private function getRefgroupe(
        RouteGroupeRepository $routeGroupeRepository,
        ?Groupe $groupe = null
    ): JsonResponse
    {
        $results = $routeGroupeRepository->findEnableByGroupe($groupe);
        $data    = [];
        if (!is_iterable($results)) {
            return new JsonResponse($data);
        }

        foreach ($results as $result) {
            /** @var RouteGroupe $result */
            /** @var Groupe $groupe */
            $groupe = $result->getRefgroupe();
            /** @var EntityRoute $route */
            $route  = $result->getRefroute();
            $data[] = [
                'groupe' => $groupe->getCode(),
                'route'  => $route->getName(),
            ];
        }

        return new JsonResponse($data);
    }
}
