<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Form\ButtonsFieldsType;
use Labstag\Form\Admin\Form\ChoiceFieldsType;
use Labstag\Form\Admin\Form\DateAndTimeFieldsType;
use Labstag\Form\Admin\Form\ExtraFieldsFieldsType as FormExtraFieldsFieldsType;
use Labstag\Form\Admin\Form\HiddenFieldsType;
use Labstag\Form\Admin\Form\OtherFieldsType;
use Labstag\Form\Admin\Form\TextFieldsType;
use Labstag\FormType\MinMaxCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormType extends AbstractType
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
            'buttons',
            CollectionType::class,
            [
                'entry_type' => ButtonsFieldsType::class,
            ]
        );
        $builder->add(
            'choice',
            CollectionType::class,
            [
                'entry_type' => ChoiceFieldsType::class,
            ]
        );
        $builder->add(
            'dateandtime',
            CollectionType::class,
            [
                'entry_type' => DateAndTimeFieldsType::class,
            ]
        );
        $builder->add(
            'extra',
            CollectionType::class,
            [
                'entry_type' => FormExtraFieldsFieldsType::class,
            ]
        );
        $builder->add(
            'hidden',
            CollectionType::class,
            [
                'entry_type' => HiddenFieldsType::class,
            ]
        );
        $builder->add(
            'other',
            MinMaxCollectionType::class,
            [
                'entry_type' => OtherFieldsType::class,
            ]
        );
        $builder->add(
            'text',
            MinMaxCollectionType::class,
            [
                'entry_type'   => TextFieldsType::class,
                'allow_add'    => true,
                'allow_delete' => true,
            ]
        );
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => true,
                'first_options'   => ['label' => 'Password'],
                'second_options'  => ['label' => 'Repeat Password'],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            []
        );
    }
}
