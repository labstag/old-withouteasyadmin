<?php

namespace Labstag\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'confirm',
            CheckboxType::class,
            [
                'label'    => 'security.form.disclaimer.confirm.label',
                'help'     => 'security.form.disclaimer.confirm.help',
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'security.form.disclaimer.submit.label']
        );
        $builder->add(
            'reset',
            ResetType::class,
            ['label' => 'security.form.disclaimer.reset.label']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['csrf_token_id' => 'login']
        );
    }
}
