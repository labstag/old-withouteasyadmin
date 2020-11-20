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
    public function index(PhoneUserRepository $phoneUserRepository): Response
    {
        return $this->adminCrudService->list(
            $phoneUserRepository,
            'findAllForAdmin',
            'admin/phone_user/index.html.twig',
            ['new' => 'admin_phoneuser_new']
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new PhoneUser(),
            PhoneUserType::class,
            ['list' => 'admin_phoneuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_phoneuser_show", methods={"GET"})
     */
    public function show(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->read(
            $phoneUser,
            'admin/phone_user/show.html.twig',
            [
                'delete' => 'admin_phoneuser_delete',
                'list'   => 'admin_phoneuser_index',
                'edit'   => 'admin_phoneuser_edit',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_phoneuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->update(
            PhoneUserType::class,
            $phoneUser,
            [
                'delete' => 'admin_phoneuser_delete',
                'list'   => 'admin_phoneuser_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_phoneuser_delete", methods={"DELETE"})
     */
    public function delete(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->delete(
            $phoneUser,
            'admin_phoneuser_index'
        );
    }
}
