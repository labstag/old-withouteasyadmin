<?php

namespace Labstag\Form\Security;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $first  = [
            'label' => $this->translator->trans('changepassword.password.label', [], 'security.form'),
            'help'  => $this->translator->trans('changepassword.password.help', [], 'security.form'),
        ];
        $second = [
            'label' => $this->translator->trans('changepassword.repeatpassword.label', [], 'security.form'),
            'help'  => $this->translator->trans('changepassword.repeatpassword.help', [], 'security.form'),
        ];
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'           => PasswordType::class,
                'first_options'  => $first,
                'second_options' => $second,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => '.changepassword.password.label']
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
