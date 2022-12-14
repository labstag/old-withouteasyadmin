<?php

namespace Labstag\Form\Admin;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class LinkType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('link.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('link.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('link.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'address',
            UrlType::class,
            [
                'label' => $this->translator->trans('link.address.label', [], 'admin.form'),
                'help'  => $this->translator->trans('link.address.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('link.address.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        // Configure your form options here
        $optionsResolver->setDefaults(
            ['entity' => null]
        );
    }
}
