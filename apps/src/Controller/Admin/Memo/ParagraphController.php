<?php

namespace Labstag\Controller\Admin\Memo;

use Labstag\Entity\Memo;
use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/memo/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_memo_paragraph_add')]
    public function add(
        ParagraphRequestHandler $handler,
        Memo $memo,
        ParagraphRepository $repository,
        Request $request
    ): RedirectResponse
    {
        $paragraph = new Paragraph();
        $old       = clone $paragraph;
        $paragraph->setPosition(count($memo->getParagraphs()) + 1);
        $paragraph->setMemo($memo);
        $paragraph->settype($request->get('data'));
        $repository->add($paragraph);
        $handler->handle($old, $paragraph);

        return $this->redirectToRoute('admin_memo_paragraph_list', ['id' => $memo->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_memo_paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getMemo(),
            'admin_memo_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'admin_memo_paragraph_list')]
    public function list(Memo $memo): Response
    {
        return $this->listTwig(
            'admin_memo_paragraph_show',
            $memo->getParagraphs(),
            'admin_memo_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_memo_paragraph_show')]
    public function show(Paragraph $paragraph, ParagraphRequestHandler $handler)
    {
        return parent::showTwig($paragraph, $handler);
    }
}
