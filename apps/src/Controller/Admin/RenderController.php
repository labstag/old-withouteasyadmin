<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Render;
use Labstag\Form\Admin\RenderType;
use Labstag\Form\Admin\Search\RenderType as SearchRenderType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\RenderRequestHandler;
use Labstag\Search\RenderSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/render')]
class RenderController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_render_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_render_new', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Render $render, RenderRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            RenderType::class,
            !is_null($render) ? $render : new Render(),
            'admin/render/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_render_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_render_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Render::class,
            'admin/render/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_render_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_render_preview', methods: ['GET'])]
    public function showOrPreview(Render $render): Response
    {
        return $this->renderShowOrPreview(
            $render,
            'admin/render/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_render_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_render_index',
            'new'      => 'admin_render_new',
            'preview'  => 'admin_render_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_render_show',
            'trash'    => 'admin_render_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchRenderType::class,
            'data' => new RenderSearch(),
        ];
    }

    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('render.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_index',
                ],
                [
                    'title' => $this->translator->trans('render.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_edit',
                ],
                [
                    'title' => $this->translator->trans('render.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_new',
                ],
                [
                    'title' => $this->translator->trans('render.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_trash',
                ],
                [
                    'title' => $this->translator->trans('render.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_preview',
                ],
                [
                    'title' => $this->translator->trans('render.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_render_show',
                ],
            ]
        );
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_bookmark' => $this->translator->trans('render.title', [], 'admin.header'),
            ]
        );
    }
}
