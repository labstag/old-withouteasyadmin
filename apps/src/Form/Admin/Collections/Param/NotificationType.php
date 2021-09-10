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
    ): void {
        $builder->add(
            'type',
            TextType::class,
            [
                'label' => $this->translator->trans('param.notification.type.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.notification.type.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'mail',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.notification.mail.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.notification.mail.help', [], 'admin.form'),
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
                'label'   => $this->translator->trans('param.notification.notify.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.notification.notify.help', [], 'admin.form'),
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
