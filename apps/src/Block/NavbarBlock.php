<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Navbar;
use Labstag\Form\Admin\Block\NavbarType;
use Labstag\Lib\BlockLib;

class NavbarBlock extends BlockLib
{
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
        return $this->render(
            $this->getBlockFile('navbar'),
            [
                'block' => $navbar,
                'content' => $content
            ]
        );
    }
}
