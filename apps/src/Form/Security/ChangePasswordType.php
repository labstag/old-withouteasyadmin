<?php

namespace Labstag\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'           => PasswordType::class,
                'label'          => 'password',
                'first_options'  => [
                    'label' => 'security.form.changepassword.password.label',
                    'help'  => 'security.form.changepassword.password.help',
                ],
                'second_options' => [
                    'label' => 'security.form.changepassword.repeatpassword.label',
                    'help'  => 'security.form.changepassword.repeatpassword.help',
                ],
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'security.form.changepassword.password.label']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['csrf_token_id' => 'changepassword']
        );
    }
}
