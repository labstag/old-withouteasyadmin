<?php

namespace Labstag\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => 'security.form.login.username.label',
                'help' => 'security.form.login.username.help',
            ]
        );
        $builder->add(
            'password',
            PasswordType::class,
            [
                'label' => 'security.form.login.password.label',
                'help' => 'security.form.login.password.help',
            ]
        );
        $builder->add(
            'remember_me',
            CheckboxType::class,
            [
                'label' => 'security.form.login.remember_me.label',
                'help' => 'security.form.login.remember_me.help',
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'security.form.login.username.label',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id'   => 'authenticate',
            ]
        );
    }
}
