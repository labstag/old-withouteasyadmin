<?php

namespace Labstag\Form\Gestion\Collections\Param;

use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'activate',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.disclaimer.activate.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.disclaimer.activate.help', [], 'gestion.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.disclaimer.activate.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.disclaimer.title.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.disclaimer.title.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.disclaimer.title.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'message',
            WysiwygType::class,
            [
                'label'    => $this->translator->trans('param.disclaimer.message.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.disclaimer.message.help', [], 'gestion.form'),
                'required' => false,
            ]
        );
        $formBuilder->add(
            'url-redirect',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.disclaimer.url-redirect.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.disclaimer.url-redirect.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.disclaimer.url-redirect.placeholder',
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
