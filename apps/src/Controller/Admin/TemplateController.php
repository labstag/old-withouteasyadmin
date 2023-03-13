<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Template;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/template', name: 'admin_template_')]
class TemplateController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
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
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/template/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
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
        $domainLib = $this->domainService->getDomain(Template::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
