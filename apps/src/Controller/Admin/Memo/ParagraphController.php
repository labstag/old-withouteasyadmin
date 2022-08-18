<?php

namespace Labstag\Controller\Admin\Memo;

use Labstag\Entity\Memo;
use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/memo/paragraph')]
class ParagraphController extends ControllerLib
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
    public function delete(Paragraph $paragraph, ParagraphRepository $repository): Response
    {
        $memo = $paragraph->getMemo();
        $repository->remove($paragraph);
        $this->addFlash('success', 'Paragraph supprimée.');

        return $this->redirectToRoute('admin_memo_edit', ['id' => $memo->getId(), '_fragment' => 'paragraph-list']);
    }

    #[Route(path: '/', name: 'admin_memo_paragraph_index', methods: ['GET'])]
    public function iframe()
    {
        return $this->render('admin/paragraph/iframe.html.twig');
    }

    #[Route(path: '/list/{id}', name: 'admin_memo_paragraph_list')]
    public function list(Memo $memo): Response
    {
        $typeparagraphs = $this->getParameter('paragraphs');

        return $this->render(
            'admin/paragraph/list.html.twig',
            [
                'paragraphs' => $memo->getParagraphs(),
                'urledit'    => 'admin_memo_paragraph_show',
                'urldelete'  => 'admin_memo_paragraph_delete',
                'types'      => $typeparagraphs['types'],
            ]
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_memo_paragraph_show')]
    public function show(
        ParagraphRequestHandler $handler,
        Paragraph $paragraph,
        ParagraphRepository $repository,
        Request $request
    )
    {
        $form = $this->createForm(
            ParagraphType::class,
            $paragraph
        );
        $form->handleRequest($request);
        $old = clone $paragraph;
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($paragraph);
            $this->addFlash('success', 'Paragraph sauvegardé.');
            $handler->handle($old, $paragraph);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Erreur lors de la modification.');
        }

        $typeparagraphs = $this->getParameter('paragraphs');

        return $this->renderForm(
            'admin/paragraph/show.html.twig',
            [
                'types'     => $typeparagraphs['types'],
                'paragraph' => $paragraph,
                'form'      => $form,
            ]
        );
    }
}
