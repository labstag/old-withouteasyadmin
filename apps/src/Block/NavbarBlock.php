<?php

namespace Labstag\Block;

use Knp\Menu\ItemInterface;
use Labstag\Entity\Block\Navbar;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\Block\NavbarType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class NavbarBlock extends BlockLib implements BlockInterface
{
    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

        return 'navbar';
    }

    public function getEntity(): string
    {
        return Navbar::class;
    }

    public function getForm(): string
    {
        return NavbarType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('navbar.name', [], 'block');
    }

    public function getType(): string
    {
        return 'navbar';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): ?Response
    {
        if (!$entityBlock instanceof Navbar) {
            return null;
        }

        $menu = $entityBlock->getMenu();
        $item = ($menu instanceof Menu) ? $this->menuService->createMenu($menu) : '';
        $show = ($item instanceof ItemInterface) ? (0 != count($item->getChildren())) : false;

        return $this->render(
            $this->getTemplateFile($this->getCode($entityBlock, $entityFront)),
            [
                'show'  => $show,
                'item'  => $item,
                'block' => $entityBlock,
            ]
        );
    }
}
