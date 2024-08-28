<?php

namespace Labstag\Controller\Gestion\History\Paragraph;

use Labstag\Entity\Chapter;
use Labstag\Entity\Paragraph;
use Labstag\Service\Gestion\ParagraphService;
use Labstag\Service\GestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/history/chapter/paragraph', name: 'gestion_chapter_paragraph_')]
class ChapterController extends AbstractController
{
    public function __construct(
        protected GestionService $gestionService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Chapter $chapter
    ): RedirectResponse
    {
        return $this->paragraph()->add($chapter);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Chapter $chapter): Response
    {
        return $this->paragraph()->list($chapter->getParagraphs());
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
            'gestion_chapter_paragraph_list',
            'gestion_chapter_edit',
            'gestion_chapter_paragraph_show',
            'gestion_chapter_paragraph_delete'
        );

        return $paragraph;
    }
}
