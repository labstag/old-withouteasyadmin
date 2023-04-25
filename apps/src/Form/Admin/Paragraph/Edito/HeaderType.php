<?php

namespace Labstag\Form\Admin\Paragraph\Edito;

use Labstag\Entity\Paragraph\Edito\Header;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeaderType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Header::class,
            ]
        );
    }
}
