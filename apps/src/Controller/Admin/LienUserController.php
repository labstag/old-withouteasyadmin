<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\LienUser;
use Labstag\Form\Admin\LienUserType;
use Labstag\Repository\LienUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/lien")
 */
class LienUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_lienuser_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        LienUserRepository $lienUserRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $lienUserRepository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/lien_user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_lienuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lienUser = new LienUser();
        $form     = $this->createForm(LienUserType::class, $lienUser);
        $return   = $this->newForm($request, $form, $lienUser);
        if ($return) {
            return $this->redirectToRoute('admin_lienuser_index');
        }

        return $this->render(
            'admin/lien_user/new.html.twig',
            [
                'lien_user' => $lienUser,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_lienuser_show", methods={"GET"})
     */
    public function show(LienUser $lienUser): Response
    {
        return $this->render(
            'admin/lien_user/show.html.twig',
            ['lien_user' => $lienUser]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_lienuser_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LienUser $lienUser): Response
    {
        $form   = $this->createForm(LienUserType::class, $lienUser);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_lienuser_index');
        }

        return $this->render(
            'admin/lien_user/edit.html.twig',
            [
                'lien_user' => $lienUser,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_lienuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LienUser $lienUser): Response
    {
        $this->deleteEntity($request, $lienUser);

        return $this->redirectToRoute('admin_lienuser_index');
    }
}
