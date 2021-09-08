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

        $this->setInputText($builder);
        $this->setInputTrueFalsePartie1($builder);
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
        $this->setInputTrueFalsePartie2($builder);
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

    private function setInputTrueFalsePartie1($builder)
    {
        $tab = [
            'groupServices'  => [
                'label' => $this->translator->trans('param.tarteaucitron.groupServices.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.groupServices.help', [], 'admin.form'),
            ],
            'showAlertSmall' => [
                'label' => $this->translator->trans('param.tarteaucitron.showAlertSmall.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.showAlertSmall.help', [], 'admin.form'),
            ],
            'cookieslist'    => [
                'label' => $this->translator->trans('param.tarteaucitron.cookieslist.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.cookieslist.help', [], 'admin.form'),
            ],
            'closePopup'     => [
                'label' => $this->translator->trans('param.tarteaucitron.closePopup.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.closePopup.help', [], 'admin.form'),
            ],
            'showIcon'       => [
                'label' => $this->translator->trans('param.tarteaucitron.showIcon.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.showIcon.help', [], 'admin.form'),
            ],
        ];
        $this->setInputTrueFalse($builder, $tab);
    }

    private function setInputTrueFalsePartie2($builder)
    {
        $tab = [
            'adblocker'               => [
                'label' => $this->translator->trans('param.tarteaucitron.adblocker.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.adblocker.help', [], 'admin.form'),
            ],
            'DenyAllCta'              => [
                'label' => $this->translator->trans('param.tarteaucitron.DenyAllCta.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.DenyAllCta.help', [], 'admin.form'),
            ],
            'AcceptAllCta'            => [
                'label' => $this->translator->trans('param.tarteaucitron.AcceptAllCta.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.AcceptAllCta.help', [], 'admin.form'),
            ],
            'highPrivacy'             => [
                'label' => $this->translator->trans('param.tarteaucitron.highPrivacy.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.highPrivacy.help', [], 'admin.form'),
            ],
            'handleBrowserDNTRequest' => [
                'label' => $this->translator->trans(
                    'param.tarteaucitron.handleBrowserDNTRequest.label',
                    [],
                    'admin.form'
                ),
                'help'  => $this->translator->trans(
                    'param.tarteaucitron.handleBrowserDNTRequest.help',
                    [],
                    'admin.form'
                ),
            ],
            'removeCredit'            => [
                'label' => $this->translator->trans('param.tarteaucitron.removeCredit.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.removeCredit.help', [], 'admin.form'),
            ],
            'moreInfoLink'            => [
                'label' => $this->translator->trans('param.tarteaucitron.moreInfoLink.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.moreInfoLink.help', [], 'admin.form'),
            ],
        ];
        $this->setInputTrueFalse($builder, $tab);
    }

    private function setInputTrueFalse($builder, $tab)
    {
        foreach ($tab as $id => $row) {
            $builder->add(
                $id,
                ChoiceType::class,
                [
                    'label'   => $row['label'],
                    'help'    => $row['help'],
                    'choices' => [
                        'Non' => '0',
                        'Oui' => '1',
                    ],
                ]
            );
        }
    }

    private function setInputText($builder)
    {
        $tab = [
            'hashtag'     => [
                'label' => $this->translator->trans('param.tarteaucitron.hashtag.label', [], 'admin'),
                'help'  => $this->translator->trans('param.tarteaucitron.hashtag.help', [], 'admin'),
            ],
            'cookieName'  => [
                'label' => $this->translator->trans('param.tarteaucitron.cookieName.label', [], 'admin'),
                'help'  => $this->translator->trans('param.tarteaucitron.cookieName.help', [], 'admin'),
            ],
            'orientation' => [
                'label' => $this->translator->trans('param.tarteaucitron.orientation.label', [], 'admin'),
                'help'  => $this->translator->trans('param.tarteaucitron.orientation.help', [], 'admin'),
            ],
        ];
        foreach ($tab as $id => $row) {
            $builder->add(
                $id,
                TextType::class,
                [
                    'label' => $row['label'],
                    'help'  => $row['help'],
                ]
            );
        }
    }
}
