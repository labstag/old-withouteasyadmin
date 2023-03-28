<?php

namespace Labstag\Form\Admin\Paragraph;

use Labstag\Entity\Paragraph\History;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => History::class,
            ]
        );
    }
}
