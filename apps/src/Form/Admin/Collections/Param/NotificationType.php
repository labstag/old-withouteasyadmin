<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('param.notification.type.label', [], 'form'),
                'help'  => $this->translator->trans('param.notification.type.help', [], 'form'),
            ]
        );
        $builder->add(
            'mail',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.notification.mail.label', [], 'form'),
                'help'    => $this->translator->trans('param.notification.mail.help', [], 'form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $builder->add(
            'notify',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.notification.notify.label', [], 'form'),
                'help'    => $this->translator->trans('param.notification.notify.help', [], 'form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
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
