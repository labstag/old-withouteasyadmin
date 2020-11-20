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
    public function index(EmailUserRepository $emailUserRepository): Response
    {
        return $this->adminCrudService->list(
            $emailUserRepository,
            'findAllForAdmin',
            'admin/email_user/index.html.twig',
            ['new' => 'admin_emailuser_new']
        );
    }

    /**
     * @Route("/new", name="admin_emailuser_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new EmailUser(),
            EmailUserType::class,
            ['list' => 'admin_emailuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_emailuser_show", methods={"GET"})
     */
    public function show(EmailUser $emailUser): Response
    {
        return $this->adminCrudService->read(
            $emailUser,
            'admin/email_user/show.html.twig',
            [
                'delete' => 'admin_emailuser_delete',
                'edit'   => 'admin_emailuser_edit',
                'list'   => 'admin_emailuser_index',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_emailuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(EmailUser $emailUser): Response
    {
        return $this->adminCrudService->update(
            EmailUserType::class,
            $emailUser,
            [
                'delete' => 'admin_emailuser_delete',
                'list'   => 'admin_emailuser_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_emailuser_delete", methods={"DELETE"})
     */
    public function delete(EmailUser $emailUser): Response
    {
        return $this->adminCrudService->delete(
            $emailUser,
            'admin_emailuser_index'
        );
    }
}
