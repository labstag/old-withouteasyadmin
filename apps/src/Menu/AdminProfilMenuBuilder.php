<?php

namespace Labstag\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminProfilMenuBuilder
{
    use MenuTrait;

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        unset($requestStack);
        $menu = $this->factory->createItem('menulabstag');
        $menu->setChildrenAttribute('class', 'navbar-nav ml-auto');
        $menu = $this->setData($menu, 'admin-profil');

        return $menu;
    }
}
