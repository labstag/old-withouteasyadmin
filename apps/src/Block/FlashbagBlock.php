<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Flashbag;
use Labstag\Form\Admin\Block\FlashbagType;
use Labstag\Lib\BlockLib;

class FlashbagBlock extends BlockLib
{
    public function getEntity()
    {
        return Flashbag::class;
    }

    public function getForm()
    {
        return FlashbagType::class;
    }

    public function getName()
    {
        return $this->translator->trans('flashbag.name', [], 'block');
    }

    public function getType()
    {
        return 'flashbag';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Flashbag $flashbag, $content)
    {
        unset($content);

        return $this->render(
            $this->getBlockFile('flashbag'),
            ['block' => $flashbag]
        );
    }
}
