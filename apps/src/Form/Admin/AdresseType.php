<?php

namespace Labstag\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $builder->add(
            'rue',
            TextType::class,
            [
                'label' => 'admin.form.adresse.rue.label',
                'help' => 'admin.form.adresse.rue.help',
            ]
        );
        $builder->add(
            'country',
            CountryType::class,
            [
                'label' => 'admin.form.adresse.country.label',
                'help' => 'admin.form.adresse.country.help',
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
                'label' => 'admin.form.adresse.zipcode.label',
                'help' => 'admin.form.adresse.zipcode.help',
                'attr' => ['is' => 'input-codepostal'],
            ]
        );
        $builder->add(
            'ville',
            TextType::class,
            [
                'label' => 'admin.form.adresse.ville.label',
                'help' => 'admin.form.adresse.ville.help',
                'attr' => ['is' => 'input-ville'],
            ]
        );
        $builder->add(
            'gps',
            TextType::class,
            [
                'label' => 'admin.form.adresse.gps.label',
                'help' => 'admin.form.adresse.gps.help',
                'attr' => ['is' => 'input-gps'],
            ]
        );
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => 'admin.form.adresse.type.label',
                'help' => 'admin.form.adresse.type.help',
            ]
        );
        $builder->add(
            'pmr',
            CheckboxType::class,
            [
                'label' => 'admin.form.adresse.pmr.label',
                'help' => 'admin.form.adresse.pmr.help',
            ]
        );
    }
}
