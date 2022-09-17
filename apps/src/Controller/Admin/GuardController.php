<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\WorkflowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/guard')]
class GuardController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_guard_index', methods: ['GET', 'POST'])]
    public function index(
        WorkflowRepository $workflowRepository,
        GroupeRepository $groupeRepository,
        RouteRepository $routeRepository
    ): Response
    {
        $workflows = $workflowRepository->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );

        return $this->render(
            'admin/guard/index.html.twig',
            [
                'groups'    => $groupeRepository->findBy([], ['name' => 'ASC']),
                'routes'    => $routeRepository->findBy([], ['name' => 'ASC']),
                'workflows' => $workflows,
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return [...parent::setBreadcrumbsData(), ...[
            [
                'title' => $this->translator->trans('guard.title', [], 'admin.breadcrumb'),
                'route' => 'admin_guard_index',
            ],
        ]];
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [...$headers, ...[
            'admin_guard' => $this->translator->trans('guard.title', [], 'admin.header'),
        ]];
    }
}
