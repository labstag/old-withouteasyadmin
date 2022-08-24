<?php

namespace Labstag\Form\Admin;

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
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'street',
            TextType::class,
            [
                'label' => $this->translator->trans('address.street.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.street.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('address.street.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'country',
            FlagCountryType::class
        );
        $builder->add(
            'zipcode',
            TextType::class,
            [
                'label' => $this->translator->trans('address.zipcode.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.zipcode.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-codepostal',
                    'placeholder' => $this->translator->trans('address.zipcode.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'city',
            TextType::class,
            [
                'label' => $this->translator->trans('address.city.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.city.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-city',
                    'placeholder' => $this->translator->trans('address.city.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'gps',
            TextType::class,
            [
                'label' => $this->translator->trans('address.gps.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.gps.help', [], 'admin.form'),
                'attr'  => [
                    'is'          => 'input-gps',
                    'placeholder' => $this->translator->trans('address.gps.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('address.type.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.type.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('address.type.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'pmr',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('address.pmr.label', [], 'admin.form'),
                'help'  => $this->translator->trans('address.pmr.help', [], 'admin.form'),
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['entity' => null]
        );
    }
}
