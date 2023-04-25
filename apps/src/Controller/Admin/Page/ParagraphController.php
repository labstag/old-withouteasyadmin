<?php

namespace Labstag\Controller\Admin\Page;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Service\Admin\ParagraphService;
use Labstag\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/page/paragraph', name: 'admin_page_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected AdminService $adminService
    ) {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Page $page
    ): RedirectResponse {
        return $this->paragraph()->add($page);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Page $page): Response
    {
        return $this->paragraph()->list($page->getParagraphs());
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph
    ): Response {
        return $this->paragraph()->show($paragraph);
    }

    private function paragraph(): ParagraphService
    {
        $paragraph = $this->adminService->paragraph();
        $paragraph->setUrls(
            'admin_page_paragraph_list',
            'admin_page_edit',
            'admin_page_paragraph_show',
            'admin_page_paragraph_delete'
        );

        return $paragraph;
    }
}
