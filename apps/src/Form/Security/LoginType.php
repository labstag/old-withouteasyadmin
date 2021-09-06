<?php

namespace Labstag\Form\Security;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractTypeLib
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
                'label' => $this->translator->trans('login.username.label', [], 'form'),
                'help'  => $this->translator->trans('login.username.help', [], 'form'),
            ]
        );
        $builder->add(
            'password',
            PasswordType::class,
            [
                'label' => $this->translator->trans('login.password.label', [], 'form'),
                'help'  => $this->translator->trans('login.password.help', [], 'form'),
            ]
        );
        $builder->add(
            'remember_me',
            CheckboxType::class,
            [
                'label'    => $this->translator->trans('login.remember_me.label', [], 'form'),
                'help'     => $this->translator->trans('login.remember_me.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => '.login.username.label']
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
