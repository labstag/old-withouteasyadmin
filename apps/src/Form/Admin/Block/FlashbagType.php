<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Flashbag;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlashbagType extends BlockAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Flashbag::class,
            ]
        );
    }
}
