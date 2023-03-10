<?php

namespace Labstag\Controller\Admin\Page;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/page/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_page_paragraph_add')]
    public function add(
        ParagraphService $paragraphService,
        Page $page,
        Request $request
    ): RedirectResponse {
        $paragraphService->add($page, $request->get('data'));

        return $this->redirectToRoute('admin_page_paragraph_list', ['id' => $page->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_page_paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getPage(),
            'admin_page_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'admin_page_paragraph_list')]
    public function list(Page $page): Response
    {
        return $this->listTwig(
            'admin_page_paragraph_show',
            $page->getParagraphs(),
            'admin_page_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_page_paragraph_show')]
    public function show(
        Paragraph $paragraph,
        ParagraphRequestHandler $paragraphRequestHandler
    ): Response {
        return parent::showTwig($paragraph, $paragraphRequestHandler);
    }
}
