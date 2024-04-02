<?php

namespace Labstag\Form\Gestion\Collections\Param;

use Labstag\FormType\OauthChoiceType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthType extends AbstractTypeLib
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
                'label'   => $this->translator->trans('param.oauth.activate.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.oauth.activate.help', [], 'gestion.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.oauth.activate.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'type',
            OauthChoiceType::class,
            [
                'label' => $this->translator->trans('param.oauth.type.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.oauth.type.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('param.oauth.type.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'id',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.id.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.oauth.id.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('param.oauth.id.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'secret',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.secret.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.oauth.secret.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.oauth.secret.placeholder',
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
