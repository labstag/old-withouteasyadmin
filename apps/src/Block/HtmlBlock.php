<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Html;
use Labstag\Form\Admin\Block\HtmlType;
use Labstag\Lib\BlockLib;

class HtmlBlock extends BlockLib
{
    public function getEntity()
    {
        return Html::class;
    }

    public function getForm()
    {
        return HtmlType::class;
    }

    public function getName()
    {
        return $this->translator->trans('html.name', [], 'block');
    }

    public function getType()
    {
        return 'html';
    }

    public function show(Html $html)
    {
        return $this->render(
            $this->getBlockFile('html'),
            ['block' => $html]
        );
    }
}
