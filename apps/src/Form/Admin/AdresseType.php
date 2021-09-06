<?php

namespace Labstag\Form\Admin;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AdresseType extends AbstractTypeLib
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
                'label' => $this->translator->trans('adresse.rue.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.rue.help', [], 'form'),
            ]
        );
        $builder->add(
            'country',
            CountryType::class,
            [
                'label' => $this->translator->trans('adresse.country.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.country.help', [], 'form'),
                'attr'  => [
                    'is'      => 'select-country',
                    'choices' => 'true',
                ],
            ]
        );
        $builder->add(
            'zipcode',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.zipcode.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.zipcode.help', [], 'form'),
                'attr'  => ['is' => 'input-codepostal'],
            ]
        );
        $builder->add(
            'ville',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.ville.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.ville.help', [], 'form'),
                'attr'  => ['is' => 'input-ville'],
            ]
        );
        $builder->add(
            'gps',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.gps.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.gps.help', [], 'form'),
                'attr'  => ['is' => 'input-gps'],
            ]
        );
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.type.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.type.help', [], 'form'),
            ]
        );
        $builder->add(
            'pmr',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('adresse.pmr.label', [], 'form'),
                'help'  => $this->translator->trans('adresse.pmr.help', [], 'form'),
            ]
        );
    }
}
