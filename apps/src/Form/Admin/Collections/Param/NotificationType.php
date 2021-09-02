<?php

namespace Labstag\Form\Admin\Collections\Param;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
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
                'label' => 'admin.form.param.notification.type.label',
                'help'  => 'admin.form.param.notification.type.help',
            ]
        );
        $builder->add(
            'mail',
            ChoiceType::class,
            [
                'label'   => 'admin.form.param.notification.mail.label',
                'help'    => 'admin.form.param.notification.mail.help',
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
                'label'   => 'admin.form.param.notification.notify.label',
                'help'    => 'admin.form.param.notification.notify.help',
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
