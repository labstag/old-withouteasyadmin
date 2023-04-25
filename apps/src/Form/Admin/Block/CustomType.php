<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Custom;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomType extends BlockAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Custom::class,
            ]
        );
    }
}
