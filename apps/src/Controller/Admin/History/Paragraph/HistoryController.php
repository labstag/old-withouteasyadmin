<?php

namespace Labstag\Controller\Admin\History\Paragraph;

use Labstag\Entity\History;
use Labstag\Entity\Paragraph;
use Labstag\Service\Admin\ParagraphService;
use Labstag\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/history/paragraph', name: 'admin_history_paragraph_')]
class HistoryController extends AbstractController
{
    public function __construct(
        protected AdminService $adminService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        History $history
    ): RedirectResponse
    {
        return $this->paragraph()->add($history);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(History $history): Response
    {
        return $this->paragraph()->list($history->getParagraphs());
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph
    ): Response
    {
        return $this->paragraph()->show($paragraph);
    }

    private function paragraph(): ParagraphService
    {
        $paragraph = $this->adminService->paragraph();
        $paragraph->setUrls(
            'admin_history_paragraph_list',
            'admin_history_edit',
            'admin_history_paragraph_show',
            'admin_history_paragraph_delete'
        );

        return $paragraph;
    }
}
