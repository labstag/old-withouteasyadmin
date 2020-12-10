<?php

namespace Labstag\Form\Admin\Collections\Form;

use Symfony\Component\Form\AbstractType;
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

class TextFieldsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add(
            'text',
            TextType::class,
            ['help' => 'help']
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                'help' => 'help',
                'attr' => ['placeholder' => 'aa'],
            ]
        );
        $builder->add(
            'integer',
            IntegerType::class,
            ['help' => 'help']
        );
        $builder->add(
            'money',
            MoneyType::class,
            ['help' => 'help']
        );
        $builder->add(
            'number',
            NumberType::class,
            ['help' => 'help']
        );
        $builder->add(
            'password',
            PasswordType::class,
            ['help' => 'help']
        );
        $builder->add(
            'percent',
            PercentType::class,
            ['help' => 'help']
        );
        $builder->add(
            'search',
            SearchType::class,
            ['help' => 'help']
        );
        $builder->add(
            'url',
            UrlType::class,
            ['help' => 'help']
        );
        $builder->add(
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
        $builder->add(
            'tel',
            TelType::class,
            ['help' => 'help']
        );
        $builder->add(
            'color',
            ColorType::class,
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
