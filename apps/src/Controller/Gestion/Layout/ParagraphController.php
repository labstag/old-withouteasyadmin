<?php

namespace Labstag\Controller\Gestion\Layout;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph;
use Labstag\Service\Gestion\ParagraphService;
use Labstag\Service\GestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/layout/paragraph', name: 'admin_layout_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected GestionService $gestionService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Layout $layout
    ): RedirectResponse
    {
        return $this->paragraph()->add($layout);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Layout $layout): Response
    {
        return $this->paragraph()->list($layout->getParagraphs());
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
            'admin_layout_paragraph_list',
            'admin_layout_edit',
            'admin_layout_paragraph_show',
            'admin_layout_paragraph_delete'
        );

        return $paragraph;
    }
}
