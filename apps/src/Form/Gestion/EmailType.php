<?php

namespace Labstag\Form\Gestion;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\EmailType as TypeEmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class EmailType extends AbstractTypeLib
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
            'address',
            TypeEmailType::class,
            [
                'label' => $this->translator->trans('email.address.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('email.address.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('email.address.placeholder', [], 'gestion.form'),
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
