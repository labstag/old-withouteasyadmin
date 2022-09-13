<?php

namespace Labstag\Form\Admin\Paragraph\Bookmark;

use Labstag\Entity\Paragraph\Bookmark\Libelle;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Libelle::class,
            ]
        );
    }
}