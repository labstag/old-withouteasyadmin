<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $builder->add(
            'numero',
            TextType::class,
            [
                'attr' => ['is' => 'input-phone'],
            ]
        );
        $builder->add(
            'country',
            CountryType::class,
            [
                'attr' => ['is' => 'select-country'],
            ]
        );
        $builder->add('type');
    }
}
