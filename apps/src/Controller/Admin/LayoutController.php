<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Layout;
use Labstag\Form\Admin\LayoutType;
use Labstag\Form\Admin\Search\LayoutType as SearchLayoutType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\LayoutRequestHandler;
use Labstag\Search\LayoutSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/layout')]
class LayoutController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_layout_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_layout_new', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Layout $layout, LayoutRequestHandler $requestHandler): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            LayoutType::class,
            !is_null($layout) ? $layout : new Layout()
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_layout_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_layout_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Layout::class,
            'admin/layout/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_layout_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_layout_preview', methods: ['GET'])]
    public function showOrPreview(Layout $layout): Response
    {
        return $this->renderShowOrPreview(
            $layout,
            'admin/layout/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_layout_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_layout_index',
            'new'     => 'admin_layout_new',
            'preview' => 'admin_layout_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_layout_show',
            'trash'   => 'admin_layout_trash',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchLayoutType::class,
            'data' => new LayoutSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminTemplace(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.title', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.new', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplacePreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_trash',
            ],
            [
                'title' => $this->translator->trans('layout.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.show', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('layout.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_layout_trash',
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_layout' => $this->translator->trans('layout.title', [], 'admin.header'),
            ]
        );
    }
}
