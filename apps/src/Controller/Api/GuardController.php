<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\Route as EntityRoute;
use Labstag\Entity\RouteGroupe;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Labstag\RequestHandler\RouteGroupeRequestHandler;
use Labstag\RequestHandler\RouteUserRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/guard')]
class GuardController extends ApiControllerLib
{
    #[Route(path: '/groups/{groupe}', name: 'api_guard_group')]
    public function groupe(Groupe $groupe): JsonResponse
    {
        return $this->getRefgroupe($this->getRepository(RouteGroupe::class), $groupe);
    }

    #[Route(path: '/groups', name: 'api_guard_groups')]
    public function groupes(): JsonResponse
    {
        return $this->getRefgroupe($this->getRepository(RouteGroupe::class));
    }

    /**
     * @return Response
     */
    #[Route(path: '/setgroup/{route}/{groupe}', name: 'api_guard_setgroup')]
    public function setgroup(string $route, string $groupe, RouteGroupeRequestHandler $routeGroupeRH): JsonResponse
    {
        $post   = $this->requeststack->getCurrentRequest()->request->all();
        $data   = ['ok' => false];
        $groupe = $this->getRepository(Groupe::class)->findOneBy(['code' => $groupe]);
        $route  = $this->getRepository(EntityRoute::class)->findOneBy(['name' => $route]);
        if (empty($groupe) || empty($route) || !array_key_exists('_token', $post)) {
            $data['error'] = 'Erreur de saisie';

            return new JsonResponse($data);
        }

        $groupeId  = $groupe->getId();
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-'.$groupeId.'-route-'.$routeId,
            $post['_token']
        );
        if (!is_null($csrfToken) && $this->csrfTokenManager->isTokenValid($csrfToken)) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $routeGroupe = $this->getRepository(RouteGroupe::class)->findOneBy(
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

        $old = clone $routeGroupe;
        $routeGroupe->setState($post['state']);
        $routeGroupeRH->handle($old, $routeGroupe);
        $data['ok'] = true;

        return new JsonResponse($data);
    }

    /**
     * @return Response
     */
    #[Route(path: '/setuser/{route}/{user}', name: 'api_guard_setuser', methods: ['POST'])]
    public function setuser(string $route, string $user, RouteUserRequestHandler $routeUserRH): JsonResponse
    {
        $data  = ['ok' => false];
        $post  = $this->requeststack->getCurrentRequest()->request->all();
        $user  = $this->getRepository(User::class)->findOneBy(['username' => $user]);
        $route = $this->getRepository(EntityRoute::class)->findOneBy(['name' => $route]);
        if (empty($user) || empty($route) || !array_key_exists('_token', $post)) {
            $data['error'] = 'Erreur de saisie';

            return new JsonResponse($data);
        }

        $userId    = $user->getId();
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-'.$userId.'-route-'.$routeId,
            $post['_token']
        );
        if (!is_null($csrfToken) && $this->csrfTokenManager->isTokenValid($csrfToken)) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $routeUser = $this->getRepository(RouteUser::class)->findOneBy(
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

        $old = clone $routeUser;
        $routeUser->setState($post['state']);
        $routeUserRH->handle($old, $routeUser);
        $data['ok'] = true;

        return new JsonResponse($data);
    }

    /**
     * @return Response
     */
    #[Route(path: '/users/{user}', name: 'api_guard_user')]
    public function user(User $user): JsonResponse
    {
        $data    = [
            'groups' => [],
            'user'   => [],
        ];
        $results = $this->getRepository(RouteGroupe::class)->findEnableByGroupe($user->getRefgroupe());
        foreach ($results as $row) {
            // @var RouteGroupe $row
            $data['groups'][] = [
                'route' => $row->getRefroute()->getName(),
            ];
        }

        $results = $this->getRepository(RouteUser::class)->findEnableByUser($user);
        foreach ($results as $row) {
            // @var RouteUser $row
            $data['user'][] = [
                'route' => $row->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    private function getRefgroupe($routeGroupeRepo, ?Groupe $groupe = null): JsonResponse
    {
        $results = $routeGroupeRepo->findEnableByGroupe($groupe);
        $data    = [];
        foreach ($results as $row) {
            // @var RouteGroupe $row
            $data[] = [
                'groupe' => $row->getRefgroupe()->getCode(),
                'route'  => $row->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }
}
