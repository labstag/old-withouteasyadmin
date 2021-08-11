<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Menu;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MenuRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{

    protected string $headerTitle = 'Menu';

    protected string $urlHome = 'admin_menu_index';

    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(MenuRepository $repository)
    {
        $all = $repository->findAllCode();

        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET"})
     */
    public function new()
    {
    }

    /**
     * @Route("/link", name="admin_menu_link", methods={"GET"})
     */
    public function link()
    {
    }

    /**
     * @Route("/update/{id}", name="admin_menu_update", methods={"GET"})
     */
    public function edit(Menu $menu)
    {
        dump($menu);
    }
}
