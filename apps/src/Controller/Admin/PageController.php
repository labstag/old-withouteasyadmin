<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Page;
use Labstag\Form\Admin\Search\PageType as SearchPageType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\Search\PageSearch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/page')]
class PageController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_page_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Page $page,
        PageRequestHandler $pageRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            is_null($page) ? new Page() : $page,
            'admin/page/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_page_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_page_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            'admin/page/index.html.twig'
        );
    }

    #[Route(path: '/new', name: 'admin_page_new', methods: ['GET', 'POST'])]
    public function new(PageRepository $pageRepository, PageRequestHandler $pageRequestHandler): RedirectResponse
    {
        $page = new Page();
        $page->setName(Uuid::v1());
        $page->setFront(false);

        $old = clone $page;
        $pageRepository->add($page);
        $pageRequestHandler->handle($old, $page);

        return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_page_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_page_preview', methods: ['GET'])]
    public function showOrPreview(Page $page): Response
    {
        return $this->renderShowOrPreview(
            $page,
            'admin/page/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Page::class);
    }

    /**
     * @return array<string, \PageSearch>|array<string, class-string<\Labstag\Form\Admin\Search\PageType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchPageType::class,
            'data' => new PageSearch(),
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
                    'title' => $this->translator->trans('page.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_index',
                ],
                [
                    'title' => $this->translator->trans('page.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_edit',
                ],
                [
                    'title' => $this->translator->trans('page.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_new',
                ],
                [
                    'title' => $this->translator->trans('page.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_trash',
                ],
                [
                    'title' => $this->translator->trans('page.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_preview',
                ],
                [
                    'title' => $this->translator->trans('page.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_page_show',
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
                'admin_bookmark' => $this->translator->trans('page.title', [], 'admin.header'),
            ],
        ];
    }
}
