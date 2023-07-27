<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Page;
use Labstag\Lib\AdminControllerLib;
use Labstag\Service\Admin\Entity\PageService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/%admin_route%/page', name: 'admin_page_')]
class PageController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Page $page
    ): Response
    {
        return $this->setAdmin()->edit($page);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): RedirectResponse
    {
        return $this->setAdmin()->add();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Page $page): Response
    {
        return $this->setAdmin()->preview($page);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->setAdmin()->show($page);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): PageService
    {
        $viewService = $this->adminService->setDomain(Page::class);
        if (!$viewService instanceof PageService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
