<?php

namespace Labstag\Controller;

use Labstag\Entity\EmailUser;
use Labstag\Form\EmailUserType;
use Labstag\Repository\EmailUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/email")
 */
class EmailUserController extends AbstractController
{
    /**
     * @Route("/", name="email_user_index", methods={"GET"})
     */
    public function index(EmailUserRepository $emailUserRepository): Response
    {
        return $this->render(
            'email_user/index.html.twig',
            [
                'email_users' => $emailUserRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="email_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emailUser = new EmailUser();
        $form      = $this->createForm(EmailUserType::class, $emailUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emailUser);
            $entityManager->flush();

            return $this->redirectToRoute('email_user_index');
        }

        return $this->render(
            'email_user/new.html.twig',
            [
                'email_user' => $emailUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="email_user_show", methods={"GET"})
     */
    public function show(EmailUser $emailUser): Response
    {
        return $this->render(
            'email_user/show.html.twig',
            ['email_user' => $emailUser]
        );
    }

    /**
     * @Route("/{id}/edit", name="email_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EmailUser $emailUser): Response
    {
        $form = $this->createForm(EmailUserType::class, $emailUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('email_user_index');
        }

        return $this->render(
            'email_user/edit.html.twig',
            [
                'email_user' => $emailUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="email_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EmailUser $emailUser): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$emailUser->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emailUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('email_user_index');
    }
}
