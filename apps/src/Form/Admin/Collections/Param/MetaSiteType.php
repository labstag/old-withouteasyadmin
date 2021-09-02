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
    ): void
    {
        $builder->add(
            'theme-color',
            ColorType::class,
            [
                'label' => 'admin.form.param.metasite.theme-color.label',
                'help'  => 'admin.form.param.metasite.theme-color.help',
            ]
        );
        $builder->add(
            'viewport',
            TextType::class,
            [
                'label'    => 'admin.form.param.metasite.viewport.label',
                'help'     => 'admin.form.param.metasite.viewport.help',
                'required' => false,
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label'    => 'admin.form.param.metasite.description.label',
                'help'     => 'admin.form.param.metasite.description.help',
                'required' => false,
            ]
        );
        $builder->add(
            'keywords',
            TextType::class,
            [
                'label'    => 'admin.form.param.metasite.keywords.label',
                'help'     => 'admin.form.param.metasite.keywords.help',
                'required' => false,
            ]
        );
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
