<?php

namespace Labstag\Controller;

use Labstag\Entity\LienUser;
use Labstag\Form\LienUserType;
use Labstag\Repository\LienUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/lien")
 */
class LienUserController extends AbstractController
{
    /**
     * @Route("/", name="lien_user_index", methods={"GET"})
     */
    public function index(LienUserRepository $lienUserRepository): Response
    {
        return $this->render(
            'lien_user/index.html.twig',
            [
                'lien_users' => $lienUserRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="lien_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lienUser = new LienUser();
        $form     = $this->createForm(LienUserType::class, $lienUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lienUser);
            $entityManager->flush();

            return $this->redirectToRoute('lien_user_index');
        }

        return $this->render(
            'lien_user/new.html.twig',
            [
                'lien_user' => $lienUser,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="lien_user_show", methods={"GET"})
     */
    public function show(LienUser $lienUser): Response
    {
        return $this->render(
            'lien_user/show.html.twig',
            ['lien_user' => $lienUser]
        );
    }

    /**
     * @Route("/{id}/edit", name="lien_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LienUser $lienUser): Response
    {
        $form = $this->createForm(LienUserType::class, $lienUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lien_user_index');
        }

        return $this->render(
            'lien_user/edit.html.twig',
            [
                'lien_user' => $lienUser,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="lien_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LienUser $lienUser): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$lienUser->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lienUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lien_user_index');
    }
}
