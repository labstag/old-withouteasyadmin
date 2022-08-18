<?php

namespace Labstag\Lib;

use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\RequestHandler\ParagraphRequestHandler;

abstract class ParagraphControllerLib extends ControllerLib
{
    protected function deleteParagraph(Paragraph $paragraph, $entity, $urledit)
    {
        $repository = $this->getRepository(Paragraph::class);
        $repository->remove($paragraph);
        $this->addFlash('success', 'Paragraph supprimée.');

        return $this->redirectToRoute($urledit, ['id' => $entity->getId(), '_fragment' => 'paragraph-list']);
    }

    protected function listTwig($urledit, $paragraphs, $urldelete)
    {
        $typeparagraphs = $this->getParameter('paragraphs');

        return $this->render(
            'admin/paragraph/list.html.twig',
            [
                'paragraphs' => $paragraphs,
                'urledit'    => $urledit,
                'urldelete'  => $urldelete,
                'types'      => $typeparagraphs['types'],
            ]
        );
    }

    protected function showTwig(Paragraph $paragraph, ParagraphRequestHandler $handler)
    {
        $form       = $this->createForm(
            ParagraphType::class,
            $paragraph
        );
        $request    = $this->requeststack->getCurrentRequest();
        $repository = $this->getRepository(Paragraph::class);
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
