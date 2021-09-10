<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\WorkflowRepository;
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
        GroupeRepository $groupeRepo,
        WorkflowRepository $workflowRepo
    ): Response {
        return $this->render(
            'admin/guard/index.html.twig',
            [
                'groups'    => $groupeRepo->findBy([], ['name' => 'ASC']),
                'routes'    => $routeRepo->findBy([], ['name' => 'ASC']),
                'workflows' => $workflowRepo->findBy([], ['entity' => 'ASC', 'transition' => 'ASC']),
            ]
        );
    }

    protected function setBreadcrumbsPageAdminGuard(): array
    {
        return [
            [
                'title'        => $this->translator->trans('guard.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_guard_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_guard' => $this->translator->trans('guard.title', [], 'admin.header'),
            ]
        );
    }
}
