<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaSiteType extends AbstractTypeLib
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
                'label' => $this->translator->trans('param.metasite.theme-color.label', [], 'form'),
                'help'  => $this->translator->trans('param.metasite.theme-color.help', [], 'form'),
            ]
        );
        $builder->add(
            'viewport',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.viewport.label', [], 'form'),
                'help'     => $this->translator->trans('param.metasite.viewport.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.description.label', [], 'form'),
                'help'     => $this->translator->trans('param.metasite.description.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'keywords',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.keywords.label', [], 'form'),
                'help'     => $this->translator->trans('param.metasite.keywords.help', [], 'form'),
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
