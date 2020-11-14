<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add('numero');
        $builder->add('country');
        $builder->add('type');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Phone::class,
            ]
        );
    }
}
