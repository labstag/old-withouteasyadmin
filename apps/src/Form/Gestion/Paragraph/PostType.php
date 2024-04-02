<?php

namespace Labstag\Form\Gestion\Paragraph;

use Labstag\Entity\Paragraph\Post;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Post::class,
            ]
        );
    }
}
