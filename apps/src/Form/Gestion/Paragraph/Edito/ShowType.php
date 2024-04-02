<?php

namespace Labstag\Form\Gestion\Paragraph\Edito;

use Labstag\Entity\Paragraph\Edito\Show;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShowType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Show::class,
            ]
        );
    }
}
