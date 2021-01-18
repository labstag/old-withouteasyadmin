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

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{

    protected string $headerTitle = 'Utilisateurs';

    protected string $urlHome = 'admin_user_index';
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->adminCrudService->list(
            $userRepository,
            'findAllForAdmin',
            'admin/user/index.html.twig',
            ['new' => 'admin_user_new'],
            [
                'list'   => 'admin_user_index',
                'show'   => 'admin_user_show',
                'edit'   => 'admin_user_edit',
                'delete' => 'admin_user_delete',
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
     */
    public function show(User $user, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_user_show',
                [
                    'id' => $user->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
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
     */
    public function delete(User $user): Response
    {
        return $this->adminCrudService->delete($user);
    }
}
