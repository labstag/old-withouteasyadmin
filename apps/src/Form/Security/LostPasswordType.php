<?php

namespace Labstag\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LostPasswordType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'value',
            TextType::class,
            [
                'label'    => 'Username or email',
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'Get password']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['csrf_token_id' => 'lostpassword']
        );
    }
}
