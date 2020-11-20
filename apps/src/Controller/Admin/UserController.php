<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\User;
use Labstag\Form\Admin\UserType;
use Labstag\Repository\UserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->adminCrudService->list(
            $userRepository,
            'findAllForAdmin',
            'admin/user/index.html.twig',
            ['new' => 'admin_user_new']
        );
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new User(),
            UserType::class,
            ['list' => 'admin_user_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->adminCrudService->read(
            $user,
            'admin/user/show.html.twig',
            [
                'delete' => 'admin_user_delete',
                'list'   => 'admin_user_index',
                'edit'   => 'admin_user_edit',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(User $user): Response
    {
        return $this->adminCrudService->update(
            UserType::class,
            $user,
            [
                'delete' => 'admin_user_delete',
                'list'   => 'admin_user_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(User $user): Response
    {
        return $this->adminCrudService->delete(
            $user,
            'admin_user_index'
        );
    }
}
