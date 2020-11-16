<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\GroupeType;
use Labstag\Repository\GroupeRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/groupe")
 */
class GroupeController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_groupuser_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        GroupeRepository $groupeRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $groupeRepository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/groupe/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_groupuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $groupe = new Groupe();
        $form   = $this->createForm(GroupeType::class, $groupe);
        $return = $this->newForm($request, $form, $groupe);
        if ($return) {
            return $this->redirectToRoute('admin_groupuser_index');
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
     * @Route("/{id}", name="admin_groupuser_show", methods={"GET"})
     */
    public function show(Groupe $groupe): Response
    {
        return $this->render(
            'admin/groupe/show.html.twig',
            ['groupe' => $groupe]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_groupuser_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Groupe $groupe): Response
    {
        $form   = $this->createForm(GroupeType::class, $groupe);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_groupuser_index');
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
     * @Route("/{id}", name="admin_groupuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Groupe $groupe): Response
    {
        $this->deleteEntity($request, $groupe);

        return $this->redirectToRoute('admin_groupuser_index');
    }
}
