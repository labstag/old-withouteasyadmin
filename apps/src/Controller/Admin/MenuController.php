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

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->adminCrudService->list(
            $menuRepository,
            'findAllForAdmin',
            'admin/menu/index.html.twig',
            ['new' => 'admin_menu_new']
        );
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new Menu(),
            MenuType::class,
            ['list' => 'admin_menu_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->adminCrudService->read(
            $menu,
            'admin/menu/show.html.twig',
            [
                'delete' => 'admin_menu_delete',
                'list'   => 'admin_menu_index',
                'edit'   => 'admin_menu_edit',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_menu_edit", methods={"GET","POST"})
     */
    public function edit(Menu $menu): Response
    {
        return $this->adminCrudService->update(
            MenuType::class,
            $menu,
            [
                'delete' => 'admin_menu_delete',
                'list'   => 'admin_menu_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_menu_delete", methods={"DELETE"})
     */
    public function delete(Menu $menu): Response
    {
        return $this->adminCrudService->delete(
            $menu,
            'admin_menu_index'
        );
    }
}
