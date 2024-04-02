<?php

namespace Labstag\Controller\Gestion\Memo;

use Labstag\Entity\Memo;
use Labstag\Entity\Paragraph;
use Labstag\Service\Gestion\ParagraphService;
use Labstag\Service\GestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/memo/paragraph', name: 'gestion_memo_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected GestionService $gestionService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Memo $memo
    ): RedirectResponse
    {
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
    ): Response
    {
        return $this->paragraph()->show($paragraph);
    }

    private function paragraph(): ParagraphService
    {
        $paragraph = $this->gestionService->paragraph();
        $paragraph->setUrls(
            'gestion_memo_paragraph_list',
            'gestion_memo_edit',
            'gestion_memo_paragraph_show',
            'gestion_memo_paragraph_delete'
        );

        return $paragraph;
    }
}
