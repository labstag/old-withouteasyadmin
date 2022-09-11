<?php

namespace Labstag\Controller\Admin\Edito;

use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/edito/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_edito_paragraph_add')]
    public function add(
        ParagraphRequestHandler $handler,
        Edito $edito,
        ParagraphRepository $repository,
        Request $request
    ): RedirectResponse
    {
        $paragraph = new Paragraph();
        $old       = clone $paragraph;
        $paragraph->setPosition(count($edito->getParagraphs()) + 1);
        $paragraph->setEdito($edito);
        $paragraph->settype($request->get('data'));
        $repository->add($paragraph);
        $handler->handle($old, $paragraph);

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
    public function show(Paragraph $paragraph, ParagraphRequestHandler $handler)
    {
        return parent::showTwig($paragraph, $handler);
    }
}
