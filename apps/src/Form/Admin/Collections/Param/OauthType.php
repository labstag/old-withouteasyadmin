<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\FormType\OauthChoiceType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthType extends AbstractTypeLib
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
                'label'   => $this->translator->trans('param.oauth.activate.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.oauth.activate.help', [], 'admin.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.oauth.activate.placeholder',
                        [],
                        'admin.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'type',
            OauthChoiceType::class,
            [
                'label' => $this->translator->trans('param.oauth.type.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.oauth.type.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('param.oauth.type.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'id',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.id.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.oauth.id.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('param.oauth.id.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'secret',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.secret.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.oauth.secret.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.oauth.secret.placeholder',
                        [],
                        'admin.form'
                    ),
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
