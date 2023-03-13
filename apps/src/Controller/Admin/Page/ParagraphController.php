<?php

namespace Labstag\Controller\Admin\Page;

use Exception;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/page/paragraph', name: 'admin_page_paragraph_')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        Page $page,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($page, $data);

        return $this->redirectToRoute('admin_page_paragraph_list', ['id' => $page->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getPage(),
            'admin_page_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Page $page): Response
    {
        return $this->listTwig(
            'admin_page_paragraph_show',
            $page->getParagraphs(),
            'admin_page_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph,
        ParagraphRequestHandler $paragraphRequestHandler
    ): Response
    {
        return parent::showTwig($paragraph, $paragraphRequestHandler);
    }
}
