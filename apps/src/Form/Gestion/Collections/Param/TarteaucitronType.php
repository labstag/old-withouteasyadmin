<?php

namespace Labstag\Form\Gestion\Collections\Param;

use Labstag\FormType\CoreTextareaType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarteaucitronType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'privacyUrl',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.tarteaucitron.privacyUrl.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.tarteaucitron.privacyUrl.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.tarteaucitron.privacyUrl.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );

        $this->setInputTextAll($formBuilder);
        $this->setInputTrueFalsePartie1($formBuilder);
        $formBuilder->add(
            'iconPosition',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.tarteaucitron.iconPosition.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.tarteaucitron.iconPosition.help', [], 'gestion.form'),
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.tarteaucitron.iconPosition.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
                'choices' => [
                    'BottomRight' => 'BottomRight',
                    'BottomLeft'  => 'BottomLeft',
                    'TopRight'    => 'TopRight',
                    'TopLeft'     => 'TopLeft',
                ],
            ]
        );
        $this->setInputTrueFalsePartie2($formBuilder);
        $formBuilder->add(
            'readmoreLink',
            UrlType::class,
            [
                'label'    => $this->translator->trans('param.tarteaucitron.readmoreLink.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('param.tarteaucitron.readmoreLink.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'param.tarteaucitron.readmoreLink.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'mandatory',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.tarteaucitron.mandatory.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('param.tarteaucitron.mandatory.help', [], 'gestion.form'),
                'attr'    => [
                    'placeholder' => $this->translator->trans(
                        'param.tarteaucitron.mandatory.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $formBuilder->add(
            'job',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('param.tarteaucitron.job.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('param.tarteaucitron.job.help', [], 'gestion.form'),
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

    private function setInputTextAll(FormBuilderInterface $formBuilder): void
    {
        $tab = [
            'hashtag'     => [
                'label'       => $this->translator->trans('param.tarteaucitron.hashtag.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.hashtag.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.hashtag.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'cookieName'  => [
                'label'       => $this->translator->trans('param.tarteaucitron.cookieName.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.cookieName.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.cookieName.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'orientation' => [
                'label'       => $this->translator->trans('param.tarteaucitron.orientation.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.orientation.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.orientation.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
        ];
        $this->setInputText($formBuilder, $tab);
    }

    private function setInputTrueFalse(
        FormBuilderInterface $formBuilder,
        array $tab
    ): void
    {
        foreach ($tab as $id => $row) {
            $formBuilder->add(
                $id,
                ChoiceType::class,
                [
                    'label'   => $row['label'],
                    'help'    => $row['help'],
                    'choices' => [
                        'Non' => '0',
                        'Oui' => '1',
                    ],
                    'attr'    => [
                        'placeholder' => $row['placeholder'],
                    ],
                ]
            );
        }
    }

    private function setInputTrueFalsePartie1(FormBuilderInterface $formBuilder): void
    {
        $tab = [
            'groupServices'  => [
                'label'       => $this->translator->trans('param.tarteaucitron.groupServices.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.groupServices.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.groupServices.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'showAlertSmall' => [
                'label'       => $this->translator->trans('param.tarteaucitron.showAlertSmall.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.showAlertSmall.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.showAlertSmall.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'cookieslist'    => [
                'label'       => $this->translator->trans('param.tarteaucitron.cookieslist.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.cookieslist.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.cookieslist.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'closePopup'     => [
                'label'       => $this->translator->trans('param.tarteaucitron.closePopup.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.closePopup.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.closePopup.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'showIcon'       => [
                'label'       => $this->translator->trans('param.tarteaucitron.showIcon.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.showIcon.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.showIcon.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
        ];
        $this->setInputTrueFalse($formBuilder, $tab);
    }

    private function setInputTrueFalsePartie2(FormBuilderInterface $formBuilder): void
    {
        $tab = [
            'adblocker'               => [
                'label'       => $this->translator->trans('param.tarteaucitron.adblocker.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.adblocker.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.adblocker.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'DenyAllCta'              => [
                'label'       => $this->translator->trans('param.tarteaucitron.DenyAllCta.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.DenyAllCta.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.DenyAllCta.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'AcceptAllCta'            => [
                'label'       => $this->translator->trans('param.tarteaucitron.AcceptAllCta.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.AcceptAllCta.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.AcceptAllCta.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'highPrivacy'             => [
                'label'       => $this->translator->trans('param.tarteaucitron.highPrivacy.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.highPrivacy.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.highPrivacy.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'handleBrowserDNTRequest' => [
                'label'       => $this->translator->trans(
                    'param.tarteaucitron.handleBrowserDNTRequest.label',
                    [],
                    'gestion.form'
                ),
                'help'        => $this->translator->trans(
                    'param.tarteaucitron.handleBrowserDNTRequest.help',
                    [],
                    'gestion.form'
                ),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.handleBrowserDNTRequest.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'removeCredit'            => [
                'label'       => $this->translator->trans('param.tarteaucitron.removeCredit.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.removeCredit.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.removeCredit.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
            'moreInfoLink'            => [
                'label'       => $this->translator->trans('param.tarteaucitron.moreInfoLink.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.tarteaucitron.moreInfoLink.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans(
                    'param.tarteaucitron.moreInfoLink.placeholder',
                    [],
                    'gestion.form'
                ),
            ],
        ];
        $this->setInputTrueFalse($formBuilder, $tab);
    }
}
