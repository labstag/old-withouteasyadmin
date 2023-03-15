<?php

namespace Labstag\Controller\Admin\Memo;

use Exception;
use Labstag\Entity\Memo;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\PublicInterface;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/memo/paragraph', name: 'admin_memo_paragraph_')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        Memo $memo,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($memo, $data);

        return $this->redirectToRoute('admin_memo_paragraph_list', ['id' => $memo->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        $memo = $paragraph->getMemo();
        if (!$memo instanceof PublicInterface) {
            throw new Exception('memo is not public interface');
        }

        return $this->deleteParagraph(
            $paragraph,
            $memo,
            'admin_memo_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Memo $memo): Response
    {
        return $this->listTwig(
            'admin_memo_paragraph_show',
            $memo->getParagraphs(),
            'admin_memo_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph
    ): Response
    {
        return parent::showTwig($paragraph);
    }
}
