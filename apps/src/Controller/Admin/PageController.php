<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Page;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\PageRepository;
use Labstag\RequestHandler\PageRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/page')]
class PageController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_page_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Page $page
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($page) ? new Page() : $page,
            'admin/page/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_page_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_page_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/page/index.html.twig'
        );
    }

    #[Route(path: '/new', name: 'admin_page_new', methods: ['GET', 'POST'])]
    public function new(PageRepository $pageRepository, PageRequestHandler $pageRequestHandler): RedirectResponse
    {
        $page = new Page();
        $page->setName(Uuid::v1());

        $old = clone $page;
        $pageRepository->add($page);
        $pageRequestHandler->handle($old, $page);

        return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_page_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_page_preview', methods: ['GET'])]
    public function showOrPreview(Page $page): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $page,
            'admin/page/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(Page::class);
    }
}
