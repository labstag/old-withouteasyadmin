<?php

namespace Labstag\Form\Admin\Collections\Form;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFieldsType extends AbstractTypeLib
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
            'text',
            TextType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'email',
            EmailType::class,
            [
                'help' => 'help',
                'attr' => ['placeholder' => 'aa'],
            ]
        );
        $formBuilder->add(
            'integer',
            IntegerType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'money',
            MoneyType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'number',
            NumberType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'password',
            PasswordType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'percent',
            PercentType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'search',
            SearchType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'url',
            UrlType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'range',
            RangeType::class,
            [
                'help' => 'help',
                'attr' => [
                    'min' => 5,
                    'max' => 50,
                ],
            ]
        );
        $formBuilder->add(
            'tel',
            TelType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'color',
            ColorType::class,
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
