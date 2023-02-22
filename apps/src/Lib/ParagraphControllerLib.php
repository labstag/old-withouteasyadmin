<?php

namespace Labstag\Lib;

use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class ParagraphControllerLib extends ControllerLib
{
    public function modalAttachmentDelete(Paragraph $paragraph, FormInterface $form): void
    {
        $entity = $this->paragraphService->getEntity($paragraph);
        $annotations = array_merge(
            $this->uploadAnnotationReader->getUploadableFields($paragraph),
            $this->uploadAnnotationReader->getUploadableFields($entity),
        );
        if (0 == count($annotations)) {
            return;
        }

        $fields = $form->all();
        $enable = $this->uploadAnnotationReader->enableAttachment($annotations, $fields);
        if (!$enable) {
            return;
        }

        $globals = $this->twigEnvironment->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['attachmentdelete'] = true;
        $this->twigEnvironment->mergeGlobals(['modal' => $modal]);
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
        $form = $this->createForm(
            ParagraphType::class,
            $paragraph
        );
        $this->modalAttachmentDelete($paragraph, $form);
        $request = $this->requeststack->getCurrentRequest();
        /** @var ParagraphRepository $repository */
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

        return $this->render(
            'admin/paragraph/show.html.twig',
            [
                'paragraph' => $paragraph,
                'form'      => $form,
            ]
        );
    }
}
