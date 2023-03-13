<?php

namespace Labstag\Controller\Admin\History\Paragraph;

use Exception;
use Labstag\Entity\History;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/history/paragraph', name: 'admin_history_paragraph_')]
class HistoryController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        History $history,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($history, $data);

        return $this->redirectToRoute('admin_history_paragraph_list', ['id' => $history->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getHistory(),
            'admin_history_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(History $history): Response
    {
        return $this->listTwig(
            'admin_history_paragraph_show',
            $history->getParagraphs(),
            'admin_history_paragraph_delete'
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
