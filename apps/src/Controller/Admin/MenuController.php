<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\MenuType;
use Labstag\Repository\MenuRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\MenuRequestHandler;

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{

    protected string $headerTitle = 'Menu';

    protected string $urlHome = 'admin_menu_index';

    /**
     * @Route("/trash", name="admin_menu_trash", methods={"GET"})
     * @Route("/", name="admin_menu_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(MenuRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/menu/index.html.twig',
            [
                'new'   => 'admin_menu_new',
                'empty' => 'admin_menu_empty',
                'trash' => 'admin_menu_trash',
                'list'  => 'admin_menu_index',
            ],
            [
                'list'    => 'admin_menu_index',
                'show'    => 'admin_menu_show',
                'preview' => 'admin_menu_preview',
                'edit'    => 'admin_menu_edit',
                'delete'  => 'admin_menu_delete',
                'destroy' => 'admin_menu_destroy',
                'restore' => 'admin_menu_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET","POST"})
     */
    public function new(MenuRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new Menu(),
            MenuType::class,
            $requestHandler,
            ['list' => 'admin_menu_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_menu_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_menu_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Menu $menu): Response
    {
        return $this->adminCrudService->showOrPreview(
            $menu,
            'admin/menu/show.html.twig',
            [
                'delete'  => 'admin_menu_delete',
                'restore' => 'admin_menu_restore',
                'destroy' => 'admin_menu_destroy',
                'list'    => 'admin_menu_index',
                'edit'    => 'admin_menu_edit',
                'trash'   => 'admin_menu_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_menu_edit", methods={"GET","POST"})
     */
    public function edit(Menu $menu, MenuRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            MenuType::class,
            $menu,
            $requestHandler,
            [
                'delete' => 'admin_menu_delete',
                'list'   => 'admin_menu_index',
                'show'   => 'admin_menu_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_menu_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_menu_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_menu_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(Menu $menu): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($menu);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_menu_empty", methods={"DELETE"})
     */
    public function empty(MenuRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
