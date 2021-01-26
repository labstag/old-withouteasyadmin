<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Groupe;
use Labstag\Entity\RouteGroupe;
use Labstag\Entity\User;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/admin/guard")
 */
class GuardController extends AdminControllerLib
{

    /**
     * @Route("/", name="admin_guard_index", methods={"GET","POST"})
     */
    public function index(
        RouteRepository $routeRepo,
        GroupeRepository $groupeRepo
    ): Response
    {
        $this->headerTitle = 'Droits';
        $this->urlHome     = 'admin_guard_index';
        return $this->render(
            'admin/guard.html.twig',
            [
                'groups' => $groupeRepo->findBy([], ['name' => 'ASC']),
                'all'    => $routeRepo->findBy([], ['name' => 'ASC']),
            ]
        );
    }

    /**
     * @Route("/groups", name="admin_guard_groups")
     */
    public function groups(RouteGroupeRepository $routeGroupeRepo): JsonResponse
    {
        $results = $routeGroupeRepo->findEnable();
        $data    = [];
        foreach ($results as $row) {
            /** @var RouteGroupe $row */
            $data[] = [
                'groupe' => $row->getRefgroupe()->getCode(),
                'route'  => $row->getRefroute()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/setgroup/{route}/{groupe}", name="admin_guard_setgroup", methods={"POST"})
     */
    public function group(
        string $route,
        string $groupe,
        Request $request,
        GroupeRepository $groupeRepo,
        RouteRepository $routeRepo,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse
    {
        $data   = [];
        $groupe = $groupeRepo->findOneBy(['code' => $groupe]);
        $route  = $routeRepo->findOneBy(['name' => $route]);
        if (empty($groupe) || empty($route)) {
            return new JsonResponse($data);
        }

        $post      = $request->request->all();
        $groupeId  = $groupe->getId();
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-' . $groupeId.'-route-'.$routeId,
            $post['_token']
        );
        if (!is_null($csrfToken) && $csrfTokenManager->isTokenValid($csrfToken)) {
            return new JsonResponse($data);
        }

        // Verification du token avant continuation
        $data = [
            $route,
            $groupe,
            $post,
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/setuser/{route}/{user}", name="admin_guard_setuser", methods={"POST"})
     */
    public function user(
        string $route,
        string $user,
        Request $request,
        UserRepository $userRepo,
        RouteRepository $routeRepo,
        CsrfTokenManagerInterface $csrfTokenManager
    ): JsonResponse
    {
        $data  = [];
        $user  = $userRepo->findOneBy(['code' => $user]);
        $route = $routeRepo->findOneBy(['name' => $route]);
        if (empty($user) || empty($route)) {
            return new JsonResponse($data);
        }

        $post      = $request->request->all();
        $userId    = $user->getId();
        $routeId   = $route->getId();
        $csrfToken = new CsrfToken(
            'guard-' . $userId.'-route-'.$routeId,
            $post['_token']
        );
        if (!is_null($csrfToken) && $csrfTokenManager->isTokenValid($csrfToken)) {
            return new JsonResponse($data);
        }

        // Verification du token avant continuation
        $data = [
            $route,
            $user,
            $post,
        ];
        return new JsonResponse($data);
    }
}
