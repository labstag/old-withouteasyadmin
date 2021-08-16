<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Menu;
use Labstag\Form\Admin\Menu\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MenuRepository;
use Labstag\RequestHandler\MenuRequestHandler;
use Symfony\Component\HttpFoundation\Response;
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
        $all     = $repository->findAllCode();
        $globals = $this->twig->getGlobals();
        $modal   = isset($globals['modal']) ? $globals['modal'] : [];

        $modal['delete'] = true;
        $this->twig->addGlobal('modal', $modal);
        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    /**
     * @Route("/add", name="admin_menu_add", methods={"GET"})
     */
    public function add(MenuRequestHandler $requestHandler): Response
    {
        return $this->create(
            new Menu(),
            PrincipalType::class,
            $requestHandler,
            ['list' => 'admin_menu_index'],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/divider/{id}", name="admin_menu_divider")
     */
    public function divider(Menu $menu): Response
    {
        dump($menu);
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET"})
     */
    public function new(MenuRequestHandler $requestHandler): Response
    {
        return $this->create(
            new Menu(),
            PrincipalType::class,
            $requestHandler,
            ['list' => 'admin_menu_index'],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/update/{id}", name="admin_menu_update", methods={"GET"})
     */
    public function edit(Menu $menu)
    {
        dump($menu);
    }
}
