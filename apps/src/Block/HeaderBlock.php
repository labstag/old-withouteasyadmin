<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Header;
use Labstag\Form\Admin\Block\HeaderType;
use Labstag\Lib\BlockLib;

class HeaderBlock extends BlockLib
{
    public function getEntity()
    {
        return Header::class;
    }

    public function getForm()
    {
        return HeaderType::class;
    }

    public function getName()
    {
        return $this->translator->trans('header.name', [], 'block');
    }

    public function getType()
    {
        return 'header';
    }

    public function isShowForm()
    {
        return true;
    }

    public function show(Header $header, $content)
    {
        unset($content);

        return $this->render(
            $this->getBlockFile('header'),
            ['block' => $header]
        );
    }
}
