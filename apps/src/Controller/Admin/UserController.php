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
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\UserRequestHandler;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{

    protected string $headerTitle = 'Utilisateurs';

    protected string $urlHome = 'admin_user_index';

    /**
     * @Route("/trash", name="admin_user_trash", methods={"GET"})
     * @Route("/", name="admin_user_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(UserRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/user/index.html.twig',
            [
                'new'   => 'admin_user_new',
                'empty' => 'admin_user_empty',
                'trash' => 'admin_user_trash',
                'list'  => 'admin_user_index',
            ],
            [
                'list'     => 'admin_user_index',
                'show'     => 'admin_user_show',
                'preview'  => 'admin_user_preview',
                'edit'     => 'admin_user_edit',
                'delete'   => 'admin_user_delete',
                'destroy'  => 'admin_user_destroy',
                'restore'  => 'admin_user_restore',
                'workflow' => 'admin_user_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(UserRequestHandler $requestHandler): Response
    {
        $user = new User();
        return $this->adminCrudService->create(
            $user,
            UserType::class,
            $requestHandler,
            ['list' => 'admin_user_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_user_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(User $user): Response
    {
        return $this->adminCrudService->showOrPreview(
            $user,
            'admin/user/show.html.twig',
            [
                'delete'  => 'admin_user_delete',
                'restore' => 'admin_user_restore',
                'destroy' => 'admin_user_destroy',
                'list'    => 'admin_user_index',
                'edit'    => 'admin_user_edit',
                'trash'   => 'admin_user_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(User $user, UserRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            UserType::class,
            $user,
            $requestHandler,
            [
                'delete' => 'admin_user_delete',
                'list'   => 'admin_user_index',
                'show'   => 'admin_user_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_user_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_user_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(User $user): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($user);
    }

    /**
     * @Route("/restore/{id}", name="admin_user_restore")
     * @Route("/empty", name="admin_user_empty", methods={"DELETE"})
     */
    public function empty(UserRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/workflow/{state}/{id}", name="admin_user_workflow", methods={"POST"})
     */
    public function workflow(User $user, string $state): Response
    {
        return $this->adminCrudService->workflow($user, $state);
    }
}
