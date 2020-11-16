<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\EmailUserType;
use Labstag\Repository\EmailUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/email")
 */
class EmailUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_emailuser_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        EmailUserRepository $emailUserRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $emailUserRepository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/email_user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_emailuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emailUser = new EmailUser();
        $form      = $this->createForm(EmailUserType::class, $emailUser);
        $return    = $this->newForm($request, $form, $emailUser);
        if ($return) {
            return $this->redirectToRoute('admin_emailuser_index');
        }

        return $this->render(
            'admin/email_user/new.html.twig',
            [
                'email_user' => $emailUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_emailuser_show", methods={"GET"})
     */
    public function show(EmailUser $emailUser): Response
    {
        return $this->render(
            'admin/email_user/show.html.twig',
            ['email_user' => $emailUser]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_emailuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(Request $request, EmailUser $emailUser): Response
    {
        $form   = $this->createForm(EmailUserType::class, $emailUser);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_emailuser_index');
        }

        return $this->render(
            'admin/email_user/edit.html.twig',
            [
                'email_user' => $emailUser,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_emailuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EmailUser $emailUser): Response
    {
        $this->deleteEntity($request, $emailUser);

        return $this->redirectToRoute('admin_emailuser_index');
    }
}
