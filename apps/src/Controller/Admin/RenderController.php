<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Render;
use Labstag\Form\Admin\Search\RenderType as SearchRenderType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\RenderRequestHandler;
use Labstag\Search\RenderSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/render')]
class RenderController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_render_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_render_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Render $render,
        RenderRequestHandler $renderRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            is_null($render) ? new Render() : $render,
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

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Render::class);
    }

    /**
     * @return array<string, \RenderSearch>|array<string, class-string<\Labstag\Form\Admin\Search\RenderType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchRenderType::class,
            'data' => new RenderSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
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

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [
            ...$headers, ...
            [
                'admin_bookmark' => $this->translator->trans('render.title', [], 'admin.header'),
            ],
        ];
    }
}
