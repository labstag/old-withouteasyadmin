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
            'admin/guard/index.html.twig',
            [
                'groups' => $groupeRepo->findBy([], ['name' => 'ASC']),
                'all'    => $routeRepo->findBy([], ['name' => 'ASC']),
            ]
        );
    }
}
