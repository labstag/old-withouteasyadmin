<?php

namespace Labstag\Form\Admin;

use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AdresseType extends AbstractTypeLib
{
    /**
     * @inheritdoc
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
                'label' => $this->translator->trans('adresse.rue.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.rue.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('adresse.rue.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'country',
            FlagCountryType::class,
            [
                'label' => $this->translator->trans('adresse.country.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.country.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'select-country',
                    'choices'     => 'true',
                    'placeholder' => $this->translator->trans('adresse.country.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'zipcode',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.zipcode.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.zipcode.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-codepostal',
                    'placeholder' => $this->translator->trans('adresse.zipcode.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'ville',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.ville.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.ville.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-ville',
                    'placeholder' => $this->translator->trans('adresse.ville.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'gps',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.gps.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.gps.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-gps',
                    'placeholder' => $this->translator->trans('adresse.gps.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('adresse.type.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.type.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('adresse.type.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'pmr',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('adresse.pmr.label', [], 'admin.form'),
                'help'  => $this->translator->trans('adresse.pmr.help', [], 'admin.form'),
            ]
        );
    }
}
