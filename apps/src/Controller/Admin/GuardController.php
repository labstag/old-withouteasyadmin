<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Groupe;
use Labstag\Entity\Route as EntityRoute;
use Labstag\Entity\Workflow;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/guard')]
class GuardController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_guard_index', methods: ['GET', 'POST'])]
    public function index() : Response
    {
        $workflows = $this->getRepository(Workflow::class)->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );
        return $this->render(
            'admin/guard/index.html.twig',
            [
                'groups'    => $this->getRepository(Groupe::class)->findBy([], ['name' => 'ASC']),
                'routes'    => $this->getRepository(EntityRoute::class)->findBy([], ['name' => 'ASC']),
                'workflows' => $workflows,
            ]
        );
    }
    protected function setBreadcrumbsPageAdminGuard(): array
    {
        return [
            [
                'title' => $this->translator->trans('guard.title', [], 'admin.breadcrumb'),
                'route' => 'admin_guard_index',
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
