<?php

namespace Labstag\Form\Gestion\Paragraph\Post;

use Labstag\Entity\Paragraph\Post\Year;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Year::class,
            ]
        );
    }
}
