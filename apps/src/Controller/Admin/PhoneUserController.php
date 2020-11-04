<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\PhoneUserType;
use Labstag\Repository\PhoneUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/phone")
 */
class PhoneUserController extends AbstractController
{
    /**
     * @Route("/", name="phone_user_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        PhoneUserRepository $phoneUserRepository
    ): Response {
        $pagination = $paginator->paginate(
            $phoneUserRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/phone_user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="phone_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phoneUser = new PhoneUser();
        $form      = $this->createForm(PhoneUserType::class, $phoneUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phoneUser);
            $entityManager->flush();

            return $this->redirectToRoute('phone_user_index');
        }

        return $this->render(
            'admin/phone_user/new.html.twig',
            [
                'phone_user' => $phoneUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="phone_user_show", methods={"GET"})
     */
    public function show(PhoneUser $phoneUser): Response
    {
        return $this->render(
            'admin/phone_user/show.html.twig',
            ['phone_user' => $phoneUser]
        );
    }

    /**
     * @Route("/{id}/edit", name="phone_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhoneUser $phoneUser): Response
    {
        $form = $this->createForm(PhoneUserType::class, $phoneUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phone_user_index');
        }

        return $this->render(
            'admin/phone_user/edit.html.twig',
            [
                'phone_user' => $phoneUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="phone_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhoneUser $phoneUser): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$phoneUser->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phoneUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phone_user_index');
    }
}
