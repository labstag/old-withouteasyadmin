<?php

namespace Labstag\Form\Admin\Collections\Param;

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
                'label' => $this->translator->trans('param.formatdate.admin.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.formatdate.admin.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.admin.placeholder',
                        [],
                        'admin.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'public',
            TextType::class,
            [
                'label' => $this->translator->trans('param.formatdate.public.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.formatdate.public.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.public.placeholder',
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
