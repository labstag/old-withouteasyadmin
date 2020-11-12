<?php

namespace Labstag\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminMenuBuilder
{
    use MenuTrait;

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        unset($requestStack);
        $menu = $this->factory->createItem('menulabstag');
        $menu->setChildrenAttribute('class', 'navbar-nav mr-auto');
        $menu = $this->setData($menu, 'admin');

        return $menu;
    }
}
