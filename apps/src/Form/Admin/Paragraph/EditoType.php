<?php

namespace Labstag\Form\Admin\Paragraph;

use Labstag\Entity\Paragraph\Edito;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Edito::class,
            ]
        );
    }
}
