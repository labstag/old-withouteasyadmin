<?php

namespace Labstag\Form\Gestion\Collections\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('param.notification.type.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.notification.type.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans(
                        'param.notification.type.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'mail',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.notification.mail.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.notification.mail.help', [], 'gestion.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.notification.mail.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'notify',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.notification.notify.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.notification.notify.help', [], 'gestion.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.notification.notify.placeholder',
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
