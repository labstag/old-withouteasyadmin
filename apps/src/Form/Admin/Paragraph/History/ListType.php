<?php

namespace Labstag\Form\Admin\Paragraph\History;

use Labstag\Entity\Paragraph\History\Liste;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Liste::class,
            ]
        );
    }
}
