<?php

namespace Labstag\Form\Gestion\Paragraph\Post;

use Labstag\Entity\Paragraph\Post\Archive;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Archive::class,
            ]
        );
    }
}
