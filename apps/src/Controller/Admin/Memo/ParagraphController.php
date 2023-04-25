<?php

namespace Labstag\Controller\Admin\Memo;

use Labstag\Entity\Memo;
use Labstag\Entity\Paragraph;
use Labstag\Service\Admin\ParagraphService;
use Labstag\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/memo/paragraph', name: 'admin_memo_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected AdminService $adminService
    ) {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Memo $memo
    ): RedirectResponse {
        return $this->paragraph()->add($memo);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Memo $memo): Response
    {
        return $this->paragraph()->list($memo->getParagraphs());
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
            'admin_memo_paragraph_list',
            'admin_memo_edit',
            'admin_memo_paragraph_show',
            'admin_memo_paragraph_delete'
        );

        return $paragraph;
    }
}
