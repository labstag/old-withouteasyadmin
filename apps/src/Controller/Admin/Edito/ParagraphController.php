<?php

namespace Labstag\Controller\Admin\Edito;

use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/edito/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_edito_paragraph_add')]
    public function add(
        ParagraphService $paragraphService,
        Edito $edito,
        Request $request
    ): RedirectResponse {
        $paragraphService->add($edito, $request->get('data'));

        return $this->redirectToRoute('admin_edito_paragraph_list', ['id' => $edito->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_edito_paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getEdito(),
            'admin_edito_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'admin_edito_paragraph_list')]
    public function list(Edito $edito): Response
    {
        return $this->listTwig(
            'admin_edito_paragraph_show',
            $edito->getParagraphs(),
            'admin_edito_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_edito_paragraph_show')]
    public function show(
        Paragraph $paragraph,
        ParagraphRequestHandler $paragraphRequestHandler
    ): Response {
        return parent::showTwig($paragraph, $paragraphRequestHandler);
    }
}
