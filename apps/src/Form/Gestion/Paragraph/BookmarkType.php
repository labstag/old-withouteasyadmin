<?php

namespace Labstag\Form\Gestion\Paragraph;

use Labstag\Entity\Paragraph\Bookmark;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookmarkType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Bookmark::class,
            ]
        );
    }
}
