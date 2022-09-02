<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Navbar;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\Block\NavbarType;
use Labstag\Lib\BlockLib;
use Labstag\Service\MenuService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class NavbarBlock extends BlockLib
{
    public function __construct(
        protected MenuService $menuService,
        TranslatorInterface $translator,
        Environment $twig
    )
    {
        parent::__construct($translator, $twig);
    }

    public function getEntity()
    {
        return Navbar::class;
    }

    public function getForm()
    {
        return NavbarType::class;
    }

    public function getName()
    {
        return $this->translator->trans('navbar.name', [], 'block');
    }

    public function getType()
    {
        return 'navbar';
    }

    public function show(Navbar $navbar, $content)
    {
        $menu = $navbar->getMenu();
        $item = ($menu instanceof Menu) ? $this->menuService->createMenu($menu) : '';
        $show = (0 != count($item->getChildren()));

        return $this->render(
            $this->getBlockFile('navbar'),
            [
                'show'    => $show,
                'item'    => $item,
                'block'   => $navbar,
                'content' => $content,
            ]
        );
    }
}
