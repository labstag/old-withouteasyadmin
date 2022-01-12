<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Layout;
use Labstag\Form\Admin\LayoutType;
use Labstag\Form\Admin\Search\LayoutType as SearchLayoutType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\LayoutRepository;
use Labstag\RequestHandler\LayoutRequestHandler;
use Labstag\Search\LayoutSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/layout")
 */
class LayoutController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_layout_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_layout_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Layout $layout,
        LayoutRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            LayoutType::class,
            !is_null($layout) ? $layout : new Layout()
        );
    }

    /**
     * @Route("/trash", name="admin_layout_trash", methods={"GET"})
     * @Route("/", name="admin_layout_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(
        EntityManagerInterface $entityManager,
        LayoutRepository $repository
    ): Response
    {
        return $this->listOrTrash(
            $entityManager,
            $repository,
            'admin/layout/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_layout_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_layout_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Layout $layout
    ): Response
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
                'title'        => $this->translator->trans('layout.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('layout.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('layout.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplacePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('layout.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('layout.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('layout.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('layout.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_layout_trash',
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
                'admin_layout' => $this->translator->trans('layout.title', [], 'admin.header'),
            ]
        );
    }
}
