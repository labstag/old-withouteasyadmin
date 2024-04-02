<?php

namespace Labstag\Form\Gestion;

use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AddressType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'street',
            TextType::class,
            [
                'label' => $this->translator->trans('address.street.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.street.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('address.street.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'country',
            FlagCountryType::class
        );
        $formBuilder->add(
            'zipcode',
            TextType::class,
            [
                'label' => $this->translator->trans('address.zipcode.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.zipcode.help', [], 'gestion.form'),
                'attr'  => [
                    'is'          => 'input-codepostal',
                    'placeholder' => $this->translator->trans('address.zipcode.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'city',
            TextType::class,
            [
                'label' => $this->translator->trans('address.city.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.city.help', [], 'gestion.form'),
                'attr'  => [
                    'is'          => 'input-city',
                    'placeholder' => $this->translator->trans('address.city.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'gps',
            TextType::class,
            [
                'label' => $this->translator->trans('address.gps.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.gps.help', [], 'gestion.form'),
                'attr'  => [
                    'is'          => 'input-gps',
                    'placeholder' => $this->translator->trans('address.gps.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('address.type.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.type.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('address.type.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'pmr',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('address.pmr.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('address.pmr.help', [], 'gestion.form'),
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        // Configure your form options here
        $optionsResolver->setDefaults(
            ['entity' => null]
        );
    }
}
