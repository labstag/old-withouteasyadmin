<?php

namespace Labstag\Controller\Admin;

use Exception;
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

#[Route(path: '/admin/page', name: 'admin_page_')]
class PageController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/page/index.html.twig'
        );
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
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
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
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
        $domainLib = $this->domainService->getDomain(Page::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
