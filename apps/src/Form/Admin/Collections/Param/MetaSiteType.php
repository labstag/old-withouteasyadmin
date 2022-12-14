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
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'theme-color',
            ColorType::class,
            [
                'label' => $this->translator->trans('param.metasite.theme-color.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.metasite.theme-color.help', [], 'admin.form'),
            ]
        );
        $formBuilder->add(
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
                        'admin.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
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
                        'admin.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
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
                        'admin.form'
                    ),
                ],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        // Configure your form options here
        $optionsResolver->setDefaults(
            []
        );
    }
}
