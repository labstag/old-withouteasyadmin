<?php

namespace Labstag\Form\Admin\Paragraph\Post;

use Labstag\Entity\Paragraph\Post\Libelle;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends ParagraphAbstractTypeLib
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Libelle::class,
            ]
        );
    }
}
