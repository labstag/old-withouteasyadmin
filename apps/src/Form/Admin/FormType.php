<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Collections\Form\ButtonsFieldsType;
use Labstag\Form\Admin\Collections\Form\ChoiceFieldsType;
use Labstag\Form\Admin\Collections\Form\DateAndTimeFieldsType;
use Labstag\Form\Admin\Collections\Form\ExtraFieldsFieldsType;
use Labstag\Form\Admin\Collections\Form\HiddenFieldsType;
use Labstag\Form\Admin\Collections\Form\OtherFieldsType;
use Labstag\Form\Admin\Collections\Form\TextFieldsType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormType extends AbstractTypeLib
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
                'entry_type' => ExtraFieldsFieldsType::class,
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
