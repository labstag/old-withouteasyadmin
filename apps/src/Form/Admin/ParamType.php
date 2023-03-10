<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Collections\Param\DisclaimerType;
use Labstag\Form\Admin\Collections\Param\FormatDateType;
use Labstag\Form\Admin\Collections\Param\MetaSiteType;
use Labstag\Form\Admin\Collections\Param\NotificationType;
use Labstag\Form\Admin\Collections\Param\OauthType;
use Labstag\Form\Admin\Collections\Param\TarteaucitronType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\FormType\UploadType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void {
        $formBuilder->add('site_title', TextType::class);
        $this->setFileType($formBuilder);
        $this->setInputs($formBuilder);
        $formBuilder->add(
            'language',
            LanguageType::class,
            [
                'multiple' => true,
                'label'    => $this->translator->trans('param.language.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.language.help', [], 'admin.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('param.language.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'generator',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.generator.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.generator.help', [], 'admin.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
                'attr'    => [
                    'placeholder' => $this->translator->trans('param.generator.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $url = 'https://unicode-org.github.io/icu/userguide/format_parse/datetime/';
        $formBuilder->add(
            'format_datetime',
            MinMaxCollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => FormatDateType::class,
                'help'         => $url,
            ]
        );
        $formBuilder->add(
            'oauth',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => OauthType::class,
            ]
        );
        $this->setMinMaxCollectionType($formBuilder);

        $formBuilder->add(
            'site_copyright',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('param.site_copyright.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.site_copyright.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('param.site_copyright.placeholder', [], 'admin.form'),
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

    private function setFileType(FormBuilderInterface $formBuilder): void
    {
        $images = [
            'image'   => [
                'label' => $this->translator->trans('param.image.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.image.help', [], 'admin.form'),
            ],
            'favicon' => [
                'label' => $this->translator->trans('param.favicon.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.favicon.help', [], 'admin.form'),
            ],
        ];
        foreach ($images as $key => $row) {
            $formBuilder->add(
                $key,
                UploadType::class,
                [
                    'label'    => $row['label'],
                    'help'     => $row['help'],
                    'required' => false,
                    'attr'     => ['accept' => 'image/*'],
                ]
            );
        }
    }

    private function setInputs(FormBuilderInterface $formBuilder): void
    {
        $inputs = [
            'title_format'    => [
                'class'       => TextType::class,
                'label'       => $this->translator->trans('param.title_format.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.title_format.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.title_format.placeholder', [], 'admin.form'),
            ],
            'robotstxt'       => [
                'class'       => TextareaType::class,
                'label'       => $this->translator->trans('param.robotstxt.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.robotstxt.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.robotstxt.placeholder', [], 'admin.form'),
            ],
            'languagedefault' => [
                'class'       => LanguageType::class,
                'label'       => $this->translator->trans('param.languagedefault.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.languagedefault.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.languagedefault.placeholder', [], 'admin.form'),
            ],
            'site_no-reply'   => [
                'class'       => EmailType::class,
                'label'       => $this->translator->trans('param.site_no-reply.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.site_no-reply.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.site_no-reply.placeholder', [], 'admin.form'),
            ],
            'site_url'        => [
                'class'       => UrlType::class,
                'label'       => $this->translator->trans('param.site_url.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.site_url.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.site_url.placeholder', [], 'admin.form'),
            ],
        ];
        foreach ($inputs as $key => $row) {
            $formBuilder->add(
                $key,
                $row['class'],
                [
                    'label' => $row['label'],
                    'help'  => $row['help'],
                    'attr'  => [
                        'placeholder' => $row['placeholder'],
                    ],
                ]
            );
        }
    }

    private function setMinMaxCollectionType(FormBuilderInterface $formBuilder): void
    {
        $mixmax = [
            'tarteaucitron' => TarteaucitronType::class,
            'meta'          => MetaSiteType::class,
            'disclaimer'    => DisclaimerType::class,
            'notification'  => NotificationType::class,
        ];
        foreach ($mixmax as $key => $entry) {
            $formBuilder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'required'     => false,
                    'label'        => ' ',
                    'allow_add'    => false,
                    'allow_delete' => false,
                    'entry_type'   => $entry,
                ]
            );
        }
    }
}
