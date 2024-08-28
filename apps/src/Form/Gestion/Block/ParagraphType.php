<?php

namespace Labstag\Form\Gestion\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphType extends BlockAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Paragraph::class,
            ]
        );
    }
}
