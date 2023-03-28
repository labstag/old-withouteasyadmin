<?php

namespace Labstag\Form\Admin\Paragraph\History;

use Labstag\Entity\Paragraph\History\Show;
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
