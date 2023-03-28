<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BreadcrumbType extends BlockAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Breadcrumb::class,
            ]
        );
    }
}
