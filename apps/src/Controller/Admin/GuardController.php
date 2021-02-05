<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
