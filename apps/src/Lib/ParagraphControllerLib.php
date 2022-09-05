<?php

namespace Labstag\Lib;

use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;

abstract class ParagraphControllerLib extends ControllerLib
{
    protected function deleteParagraph(Paragraph $paragraph, $entity, $urledit)
    {
        /** @var ParagraphRepository $repository */
        $repository = $this->getRepository(Paragraph::class);
        $repository->remove($paragraph);
        $this->addFlash('success', 'Paragraph supprimée.');

        return $this->redirectToRoute($urledit, ['id' => $entity->getId(), '_fragment' => 'paragraph-list']);
    }

    protected function listTwig($urledit, $paragraphs, $urldelete)
    {
        return $this->render(
            'admin/paragraph/list.html.twig',
            [
                'paragraphs' => $paragraphs,
                'urledit'    => $urledit,
                'urldelete'  => $urldelete,
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

        return $this->renderForm(
            'admin/paragraph/show.html.twig',
            [
                'paragraph' => $paragraph,
                'form'      => $form,
            ]
        );
    }
}
