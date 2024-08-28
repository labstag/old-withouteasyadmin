<?php

namespace Labstag\Form\Gestion\Collections\Param;

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
                'label' => $this->translator->trans('param.metasite.theme-color.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.metasite.theme-color.help', [], 'gestion.form'),
            ]
        );
        $formBuilder->add(
            'viewport',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.viewport.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.metasite.viewport.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.viewport.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'description',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.description.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.metasite.description.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.description.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'keywords',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.metasite.keywords.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.metasite.keywords.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.metasite.keywords.placeholder',
                        [],
                        'gestion.form'
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
