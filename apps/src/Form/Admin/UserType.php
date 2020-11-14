<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AdresseType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LienType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
        $builder->add('username');
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => false,
                'first_options'   => ['label' => 'Password'],
                'second_options'  => ['label' => 'Repeat Password'],
            ]
        );
        $builder->add('enable');
        $builder->add('groupe');
        $builder->add(
            'emailUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => EmailType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'phoneUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => PhoneType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'adresseUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => AdresseType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'lienUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => LienType::class,
                'by_reference' => false,
            ]
        );
        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
