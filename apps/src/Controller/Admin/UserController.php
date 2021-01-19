<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\Form\Admin\UserType;
use Labstag\Repository\UserRepository;
use Labstag\Lib\AdminControllerLib;
use Labstag\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;

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
     */
    public function indexOrTrash(RouterInterface $router, UserRepository $repository): Response
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
                'list'    => 'admin_user_index',
                'show'    => 'admin_user_show',
                'preview' => 'admin_user_preview',
                'edit'    => 'admin_user_edit',
                'delete'  => 'admin_user_delete',
                'destroy' => 'admin_user_destroy',
                'restore' => 'admin_user_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(
        RouterInterface $router,
        UserManager $userManager
    ): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_user_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        $user = new User();
        $user->setEnable(false);
        return $this->adminCrudService->create(
            $user,
            UserType::class,
            ['list' => 'admin_user_index'],
            [UserEntityEvent::class],
            $userManager
        );
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_user_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(User $user, RouterInterface $router): Response
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
    public function edit(
        User $user,
        RouterInterface $router,
        UserManager $userManager
    ): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_user_edit',
                [
                    'id' => $user->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            UserType::class,
            $user,
            [
                'delete' => 'admin_user_delete',
                'list'   => 'admin_user_index',
                'show'   => 'admin_user_show',
            ],
            [UserEntityEvent::class],
            $userManager
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
}
