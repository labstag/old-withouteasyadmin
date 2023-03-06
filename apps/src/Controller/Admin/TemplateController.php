<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Template;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/template')]
class TemplateController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_template_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_template_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Template $template
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($template) ? new Template() : $template
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_template_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_template_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/template/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_template_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_template_preview', methods: ['GET'])]
    public function showOrPreview(Template $template): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $template,
            'admin/template/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(Template::class);
    }
}
