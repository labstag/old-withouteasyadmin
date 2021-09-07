<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\FormType\CoreTextareaType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarteaucitronType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'privacyUrl',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.tarteaucitron.privacyUrl.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.tarteaucitron.privacyUrl.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $tab = [
            'hashtag',
            'cookieName',
            'orientation',
        ];

        $this->setInputText($builder, $tab);
        $tab = [
            'groupServices',
            'showAlertSmall',
            'cookieslist',
            'closePopup',
            'showIcon',
        ];
        $this->setInputTrueFalse($builder, $tab);
        $builder->add(
            'iconPosition',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.tarteaucitron.iconPosition.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.tarteaucitron.iconPosition.help', [], 'admin.form'),
                'choices' => [
                    'BottomRight' => 'BottomRight',
                    'BottomLeft'  => 'BottomLeft',
                    'TopRight'    => 'TopRight',
                    'TopLeft'     => 'TopLeft',
                ],
            ]
        );
        $tab = [
            'adblocker',
            'DenyAllCta',
            'AcceptAllCta',
            'highPrivacy',
            'handleBrowserDNTRequest',
            'removeCredit',
            'moreInfoLink',
        ];
        $this->setInputTrueFalse($builder, $tab);
        $builder->add(
            'readmoreLink',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.tarteaucitron.readmoreLink.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.tarteaucitron.readmoreLink.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'mandatory',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.tarteaucitron.mandatory.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.tarteaucitron.mandatory.help', [], 'admin.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $builder->add(
            'job',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('param.tarteaucitron.job.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.job.help', [], 'admin.form'),
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

    private function setInputTrueFalse($builder, $tab)
    {
        foreach (array_keys($tab) as $id) {
            $builder->add(
                $id,
                ChoiceType::class,
                [
                    'label'   => $this->translator->trans('param.tarteaucitron.'.$id.'.label', [], 'admin.form'),
                    'help'    => $this->translator->trans('param.tarteaucitron.'.$id.'.help', [], 'admin.form'),
                    'choices' => [
                        'Non' => '0',
                        'Oui' => '1',
                    ],
                ]
            );
        }
    }

    private function setInputText($builder, $tab)
    {
        foreach (array_keys($tab) as $id) {
            $builder->add(
                $id,
                TextType::class,
                [
                    'label' => $this->translator->trans('param.tarteaucitron.'.$id.'.label', [], 'admin.form'),
                    'help'  => $this->translator->trans('param.tarteaucitron.'.$id.'.help', [], 'admin.form'),
                ]
            );
        }
    }
}
