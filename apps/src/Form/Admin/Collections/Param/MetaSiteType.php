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
                'label' => $this->translator->trans('param.metasite.theme-color.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.metasite.theme-color.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'viewport',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.viewport.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.metasite.viewport.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.viewport.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.description.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.metasite.description.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.description.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'keywords',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.keywords.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.metasite.keywords.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.keywords.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
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
