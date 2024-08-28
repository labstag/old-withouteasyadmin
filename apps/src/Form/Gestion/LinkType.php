<?php

namespace Labstag\Form\Gestion;

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
                'label' => $this->translator->trans('link.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('link.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('link.name.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'address',
            UrlType::class,
            [
                'label' => $this->translator->trans('link.address.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('link.address.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('link.address.placeholder', [], 'gestion.form'),
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
