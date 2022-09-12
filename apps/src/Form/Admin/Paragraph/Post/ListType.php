<?php

namespace Labstag\Form\Admin\Paragraph\Post;

use Labstag\Entity\Paragraph\Post\Liste;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Liste::class,
            ]
        );
    }
}
