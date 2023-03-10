<?php

namespace Labstag\Lib;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Paragraph;
use Labstag\Form\Admin\ParagraphType;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ParagraphControllerLib extends ControllerLib
{
    public function modalAttachmentDelete(Paragraph $paragraph, FormInterface $form): void
    {
        $entity      = $this->paragraphService->getEntity($paragraph);
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

        $globals                   = $this->twigEnvironment->getGlobals();
        $modal                     = $globals['modal'] ?? [];
        $modal['attachmentdelete'] = true;
        $this->twigEnvironment->mergeGlobals(['modal' => $modal]);
    }

    protected function deleteParagraph(
        Paragraph $paragraph,
        mixed $entity,
        string $urledit
    ): RedirectResponse {
        /** @var ParagraphRepository $repository */
        $repository = $this->repositoryService->get(Paragraph::class);
        $repository->remove($paragraph);
        $this->addFlash('success', 'Paragraph supprimée.');

        return $this->redirectToRoute($urledit, ['id' => $entity->getId(), '_fragment' => 'paragraph-list']);
    }

    protected function listTwig(
        string $urledit,
        Collection $paragraphs,
        string $urldelete
    ): Response {
        return $this->render(
            'admin/paragraph/list.html.twig',
            [
                'paragraphs' => $paragraphs,
                'urledit'    => $urledit,
                'urldelete'  => $urldelete,
            ]
        );
    }

    protected function showTwig(
        Paragraph $paragraph,
        ParagraphRequestHandler $paragraphRequestHandler
    ): Response {
        $form = $this->createForm(
            ParagraphType::class,
            $paragraph
        );
        $this->modalAttachmentDelete($paragraph, $form);
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        /** @var ParagraphRepository $repository */
        $repository = $this->repositoryService->get(Paragraph::class);
        $form->handleRequest($request);
        $old    = clone $paragraph;
        $entity = $this->paragraphService->getEntity($paragraph);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($paragraph);
            $this->attachFormService->upload($entity);
            $this->addFlash('success', 'Paragraph sauvegardé.');
            $paragraphRequestHandler->handle($old, $paragraph);
            $referer = (string) $request->headers->get('referer');

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
