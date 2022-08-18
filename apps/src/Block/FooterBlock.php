<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\FooterType;
use Labstag\Lib\BlockLib;

class FooterBlock extends BlockLib
{
    public function getEntity()
    {
        return Footer::class;
    }

    public function getForm()
    {
        return FooterType::class;
    }

    public function getType()
    {
        return 'footer';
    }

    public function show(Footer $footer)
    {
        return $this->render(
            $this->getBlockFile('footer'),
            ['block' => $footer]
        );
    }
}
