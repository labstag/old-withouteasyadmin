<?php

namespace Labstag\Form\Admin\Collections\Form;

use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceFieldsType extends AbstractTypeLib
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
        $choices = [
            'Maybe' => null,
            'Yes'   => true,
            'No'    => false,
        ];
        $builder->add(
            'choice',
            ChoiceType::class,
            [
                'help'    => 'help',
                'choices' => $choices,
            ]
        );
        $builder->add(
            'choice_expanded',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'expanded' => true,
            ]
        );
        $builder->add(
            'choice_multiple',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'multiple' => true,
            ]
        );
        $builder->add(
            'choice_multiple_expanded',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'multiple' => true,
                'expanded' => true,
            ]
        );
        $builder->add(
            'country',
            FlagCountryType::class,
            ['help' => 'help']
        );
        $builder->add(
            'language',
            LanguageType::class,
            ['help' => 'help']
        );
        $builder->add(
            'locale',
            LocaleType::class,
            ['help' => 'help']
        );
        $builder->add(
            'timezone',
            TimezoneType::class,
            ['help' => 'help']
        );
        $builder->add(
            'currency',
            CurrencyType::class,
            ['help' => 'help']
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            []
        );
    }
}
