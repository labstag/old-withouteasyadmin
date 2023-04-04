<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Page;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\AdminService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/page', name: 'admin_page_')]
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
    public function new(PageRepository $pageRepository): RedirectResponse
    {
        $page = new Page();
        $page->setName(Uuid::v1());

        $pageRepository->save($page);

        return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
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

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Page::class);

        return $this->adminService;
    }
}
