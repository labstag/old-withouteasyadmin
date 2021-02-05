<?php

namespace Labstag\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class PhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add(
            'numero',
            TelType::class
        );
        $builder->add(
            'country',
            CountryType::class,
            [
                'attr' => [
                    'is'      => 'select-country',
                    'choices' => 'true',
                ],
            ]
        );
        $builder->add('type');
    }
}
