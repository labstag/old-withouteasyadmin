<?php

namespace Labstag\Controller\Gestion\Edito;

use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph;
use Labstag\Service\Gestion\ParagraphService;
use Labstag\Service\GestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/edito/paragraph', name: 'admin_edito_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected GestionService $gestionService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(Edito $edito): RedirectResponse
    {
        return $this->paragraph()->add($edito);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Edito $edito): Response
    {
        return $this->paragraph()->list($edito->getParagraphs());
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
            'admin_edito_paragraph_list',
            'admin_edito_edit',
            'admin_edito_paragraph_show',
            'admin_edito_paragraph_delete'
        );

        return $paragraph;
    }
}
