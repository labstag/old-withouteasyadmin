<?php

namespace Labstag\Form\Security;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractTypeLib
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
                'label'    => $this->translator->trans('disclaimer.confirm.label', [], 'form'),
                'help'     => $this->translator->trans('disclaimer.confirm.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => '.disclaimer.submit.label']
        );
        $builder->add(
            'reset',
            ResetType::class,
            ['label' => '.disclaimer.reset.label']
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
