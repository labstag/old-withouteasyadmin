<?php

namespace Labstag\Controller;

use Labstag\Entity\NoteInterne;
use Labstag\Form\NoteInterne1Type;
use Labstag\Repository\NoteInterneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/noteinterne")
 */
class NoteInterneController extends AbstractController
{
    /**
     * @Route("/", name="note_interne_index", methods={"GET"})
     */
    public function index(
        NoteInterneRepository $repository
    ): Response
    {
        return $this->render(
            'note_interne/index.html.twig',
            [
                'note_internes' => $repository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="note_interne_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $noteInterne = new NoteInterne();
        $form        = $this->createForm(NoteInterne1Type::class, $noteInterne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($noteInterne);
            $entityManager->flush();

            return $this->redirectToRoute('note_interne_index');
        }

        return $this->render(
            'note_interne/new.html.twig',
            [
                'note_interne' => $noteInterne,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="note_interne_show", methods={"GET"})
     */
    public function show(NoteInterne $noteInterne): Response
    {
        return $this->render(
            'note_interne/show.html.twig',
            ['note_interne' => $noteInterne]
        );
    }

    /**
     * @Route("/{id}/edit", name="note_interne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NoteInterne $noteInterne): Response
    {
        $form = $this->createForm(NoteInterne1Type::class, $noteInterne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_interne_index');
        }

        return $this->render(
            'note_interne/edit.html.twig',
            [
                'note_interne' => $noteInterne,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="note_interne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NoteInterne $noteInterne): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$noteInterne->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($noteInterne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('note_interne_index');
    }
}
