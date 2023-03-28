<?php

namespace Labstag\Form\Admin\Paragraph\Post;

use Labstag\Entity\Paragraph\Post\Header;
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
