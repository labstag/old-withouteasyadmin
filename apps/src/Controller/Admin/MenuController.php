<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Menu;
use Labstag\Lib\AdminControllerLib;
use Labstag\Service\Admin\Entity\MenuService as EntityMenuService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/menu', name: 'admin_menu_')]
class MenuController extends AdminControllerLib
{
    #[Route(path: '/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(): Response
    {
        return $this->setAdmin()->add();
    }

    #[Route(path: '/divider/{id}', name: 'divider')]
    public function divider(
        Menu $menu
    ): RedirectResponse
    {
        return $this->setAdmin()->divider($menu);
    }

    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function edit(
        Menu $menu
    ): Response
    {
        return $this->setAdmin()->edit($menu);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/move/{id}', name: 'move', methods: ['GET', 'POST'])]
    public function move(Menu $menu): Response
    {
        return $this->setAdmin()->move($menu);
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): EntityMenuService
    {
        $viewService = $this->adminService->setDomain(Menu::class);
        if (!$viewService instanceof EntityMenuService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
