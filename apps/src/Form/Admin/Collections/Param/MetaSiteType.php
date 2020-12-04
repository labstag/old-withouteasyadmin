<?php

namespace Labstag\Form\Admin\Collections\Param;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaSiteType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add('theme-color', ColorType::class);
        $builder->add('viewport', TextType::class, ['required' => false]);
        $builder->add('description', TextType::class, ['required' => false]);
        $builder->add('keywords', TextType::class, ['required' => false]);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }
}
