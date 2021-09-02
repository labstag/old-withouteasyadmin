<?php

namespace Labstag\Form\Admin\Collections\Param;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'activate',
            ChoiceType::class,
            [
                'label'   => 'admin.form.param.disclaimer.activate.label',
                'help'    => 'admin.form.param.disclaimer.activate.help',
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $builder->add(
            'title',
            TextType::class,
            [
                'label'    => 'admin.form.param.disclaimer.title.label',
                'help'     => 'admin.form.param.disclaimer.title.help',
                'required' => false,
            ]
        );
        $builder->add(
            'message',
            CKEditorType::class,
            [
                'label'    => 'admin.form.param.disclaimer.message.label',
                'help'     => 'admin.form.param.disclaimer.message.help',
                'required' => false,
            ]
        );
        $builder->add(
            'url-redirect',
            UrlType::class,
            [
                'label'    => 'admin.form.param.disclaimer.url-redirect.label',
                'help'     => 'admin.form.param.disclaimer.url-redirect.help',
                'required' => false,
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
