<?php

namespace Labstag\Form\Admin\Collections\Param;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractTypeLib
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
                'label'   => $this->translator->trans('param.disclaimer.activate.label', [], 'form'),
                'help'    => $this->translator->trans('param.disclaimer.activate.help', [], 'form'),
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
                'label'    => $this->translator->trans('param.disclaimer.title.label', [], 'form'),
                'help'     => $this->translator->trans('param.disclaimer.title.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'message',
            CKEditorType::class,
            [
                'label'    => $this->translator->trans('param.disclaimer.message.label', [], 'form'),
                'help'     => $this->translator->trans('param.disclaimer.message.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'url-redirect',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.disclaimer.url-redirect.label', [], 'form'),
                'help'     => $this->translator->trans('param.disclaimer.url-redirect.help', [], 'form'),
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
