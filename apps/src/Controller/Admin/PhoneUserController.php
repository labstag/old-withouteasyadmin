<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\PhoneUserType;
use Labstag\Repository\PhoneUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/phone")
 */
class PhoneUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_phoneuser_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        PhoneUserRepository $phoneUserRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $phoneUserRepository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/phone_user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phoneUser = new PhoneUser();
        $form      = $this->createForm(PhoneUserType::class, $phoneUser);
        $return    = $this->newForm($request, $form, $phoneUser);
        if ($return) {
            return $this->redirectToRoute('admin_phoneuser_index');
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
     * @Route("/{id}", name="admin_phoneuser_show", methods={"GET"})
     */
    public function show(PhoneUser $phoneUser): Response
    {
        return $this->render(
            'admin/phone_user/show.html.twig',
            ['phone_user' => $phoneUser]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_phoneuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(Request $request, PhoneUser $phoneUser): Response
    {
        $form   = $this->createForm(PhoneUserType::class, $phoneUser);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_phoneuser_index');
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
     * @Route("/{id}", name="admin_phoneuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhoneUser $phoneUser): Response
    {
        $this->deleteEntity($request, $phoneUser);

        return $this->redirectToRoute('admin_phoneuser_index');
    }
}
