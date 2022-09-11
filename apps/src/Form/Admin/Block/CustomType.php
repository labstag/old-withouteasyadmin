<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Custom;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Custom::class,
            ]
        );
    }
}
