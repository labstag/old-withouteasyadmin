<?php

namespace Labstag\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AdresseType extends AbstractType
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
        $builder->add('rue');
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
        $builder->add(
            'zipcode',
            TextType::class,
            [
                'attr' => ['is' => 'input-codepostal'],
            ]
        );
        $builder->add(
            'ville',
            TextType::class,
            [
                'attr' => ['is' => 'input-ville'],
            ]
        );
        $builder->add(
            'gps',
            TextType::class,
            [
                'attr' => ['is' => 'input-gps'],
            ]
        );
        $builder->add('type');
        $builder->add('pmr');
    }
}
