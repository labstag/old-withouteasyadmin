<?php

namespace Labstag\Controller\Admin\Layout;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/layout/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_layout_paragraph_add')]
    public function add(
        ParagraphRequestHandler $paragraphRequestHandler,
        Layout $layout,
        ParagraphRepository $paragraphRepository,
        Request $request
    ): RedirectResponse
    {
        $paragraph = new Paragraph();
        $old       = clone $paragraph;
        $paragraph->setPosition(count($layout->getParagraphs()) + 1);
        $paragraph->setLayout($layout);
        $paragraph->settype($request->get('data'));

        $paragraphRepository->add($paragraph);
        $paragraphRequestHandler->handle($old, $paragraph);

        return $this->redirectToRoute('admin_layout_paragraph_list', ['id' => $layout->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_layout_paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getLayout(),
            'admin_layout_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'admin_layout_paragraph_list')]
    public function list(Layout $layout): Response
    {
        return $this->listTwig(
            'admin_layout_paragraph_show',
            $layout->getParagraphs(),
            'admin_layout_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_layout_paragraph_show')]
    public function show(Paragraph $paragraph, ParagraphRequestHandler $paragraphRequestHandler)
    {
        return parent::showTwig($paragraph, $paragraphRequestHandler);
    }
}
