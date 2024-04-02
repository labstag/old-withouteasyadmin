<?php

namespace Labstag\Form\Gestion\Collections\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormatDateType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'admin',
            TextType::class,
            [
                'label' => $this->translator->trans('param.formatdate.gestion.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.formatdate.gestion.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.gestion.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'public',
            TextType::class,
            [
                'label' => $this->translator->trans('param.formatdate.public.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.formatdate.public.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.public.placeholder',
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
