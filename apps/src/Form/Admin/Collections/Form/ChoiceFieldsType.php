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
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $choices = [
            'Maybe' => null,
            'Yes'   => true,
            'No'    => false,
        ];
        $formBuilder->add(
            'choice',
            ChoiceType::class,
            [
                'help'    => 'help',
                'choices' => $choices,
            ]
        );
        $formBuilder->add(
            'choice_expanded',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'expanded' => true,
            ]
        );
        $formBuilder->add(
            'choice_multiple',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'multiple' => true,
            ]
        );
        $formBuilder->add(
            'choice_multiple_expanded',
            ChoiceType::class,
            [
                'help'     => 'help',
                'choices'  => $choices,
                'multiple' => true,
                'expanded' => true,
            ]
        );
        $formBuilder->add(
            'country',
            FlagCountryType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'language',
            LanguageType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'locale',
            LocaleType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'timezone',
            TimezoneType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'currency',
            CurrencyType::class,
            ['help' => 'help']
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            []
        );
    }
}
