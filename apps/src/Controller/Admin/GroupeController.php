<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\GroupeType;
use Labstag\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/groupe")
 */
class GroupeController extends AbstractController
{
    /**
     * @Route("/", name="groupe_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        GroupeRepository $groupeRepository
    ): Response {
        $pagination = $paginator->paginate(
            $groupeRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/groupe/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="groupe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $groupe = new Groupe();
        $form   = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groupe);
            $entityManager->flush();

            return $this->redirectToRoute('groupe_index');
        }

        return $this->render(
            'admin/groupe/new.html.twig',
            [
                'groupe' => $groupe,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="groupe_show", methods={"GET"})
     */
    public function show(Groupe $groupe): Response
    {
        return $this->render(
            'admin/groupe/show.html.twig',
            ['groupe' => $groupe]
        );
    }

    /**
     * @Route("/{id}/edit", name="groupe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Groupe $groupe): Response
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groupe_index');
        }

        return $this->render(
            'admin/groupe/edit.html.twig',
            [
                'groupe' => $groupe,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="groupe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Groupe $groupe): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$groupe->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($groupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('groupe_index');
    }
}
