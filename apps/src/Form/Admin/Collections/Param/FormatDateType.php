<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormatDateType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'admin',
            TextType::class,
            [
                'label' => $this->translator->trans('param.formatdate.admin.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.formatdate.admin.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.admin.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'public',
            TextType::class,
            [
                'label' => $this->translator->trans('param.formatdate.public.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.formatdate.public.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.formatdate.public.placeholder',
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
