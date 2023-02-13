<?php

namespace Labstag\Lib;

use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class ParagraphControllerLib extends ControllerLib
{
    public function modalAttachmentDelete(): void
    {
        $globals = $this->environment->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['attachmentdelete'] = true;
        $this->environment->addGlobal('modal', $modal);
    }

    protected function deleteParagraph(Paragraph $paragraph, $entity, string $urledit)
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

    protected function showTwig(Paragraph $paragraph, ParagraphRequestHandler $paragraphRequestHandler)
    {
        $this->modalAttachmentDelete();
        $form = $this->createForm(
            ParagraphType::class,
            $paragraph
        );
        $request = $this->requeststack->getCurrentRequest();
        $repository = $this->getRepository(Paragraph::class);
        $form->handleRequest($request);
        $old = clone $paragraph;
        $entity = $this->paragraphService->getEntity($paragraph);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($paragraph);
            $this->attachFormService->upload($entity);
            $this->addFlash('success', 'Paragraph sauvegardé.');
            $paragraphRequestHandler->handle($old, $paragraph);
            $referer = $request->headers->get('referer'); 
            return new RedirectResponse($referer);
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
