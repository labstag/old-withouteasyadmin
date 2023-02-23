<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Render;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/render')]
class RenderController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_render_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_render_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Render $render
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
            $render,
            'admin/render/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(Render::class);
    }
}
