<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Form\Admin\Collections\Param\DisclaimerType;
use Labstag\Form\Admin\Collections\Param\FormatDateType;
use Labstag\Form\Admin\Collections\Param\MetaSiteType;
use Labstag\Form\Admin\Collections\Param\NotificationType;
use Labstag\Form\Admin\Collections\Param\OauthType;
use Labstag\Form\Admin\Collections\Param\TarteaucitronType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add('site_title', TextType::class);
        $this->setFileType($builder);
        $this->setInputs($builder);
        $builder->add(
            'language',
            LanguageType::class,
            [
                'multiple' => true,
                'label'    => $this->translator->trans('param.language.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.language.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'generator',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('param.generator.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.generator.help', [], 'admin.form'),
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $url = 'https://unicode-org.github.io/icu/userguide/format_parse/datetime/';
        $builder->add(
            'format_datetime',
            MinMaxCollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => FormatDateType::class,
                'help'         => $url,
            ]
        );
        $builder->add(
            'oauth',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => OauthType::class,
            ]
        );
        $mixmax = [
            'tarteaucitron' => TarteaucitronType::class,
            'meta'          => MetaSiteType::class,
            'disclaimer'    => DisclaimerType::class,
            'notification'  => NotificationType::class,
        ];
        $this->setMinMaxCollectionType($builder, $mixmax);

        $builder->add(
            'site_copyright',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('param.site_copyright.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.site_copyright.help', [], 'admin.form'),
            ]
        );
        unset($options);
    }

    private function setMinMaxCollectionType($builder, $mixmax)
    {
        foreach ($mixmax as $key => $entry) {
            $builder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'allow_add'    => false,
                    'allow_delete' => false,
                    'entry_type'   => $entry,
                ]
            );
        }
    }

    private function setInputs($builder)
    {
        $inputs = [
            'title_format'    => [
                'class' => TextType::class,
                'label' => $this->translator->trans('param.title_format.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.title_format.help', [], 'admin.form'),
            ],
            'robotstxt'       => [
                'class' => TextareaType::class,
                'label' => $this->translator->trans('param.robotstxt.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.robotstxt.help', [], 'admin.form'),
            ],
            'languagedefault' => [
                'class' => LanguageType::class,
                'label' => $this->translator->trans('param.languagedefault.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.languagedefault.help', [], 'admin.form'),
            ],
            'site_no-reply'   => [
                'class' => EmailType::class,
                'label' => $this->translator->trans('param.site_no-reply.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.site_no-reply.help', [], 'admin.form'),
            ],
            'site_url'        => [
                'class' => UrlType::class,
                'label' => $this->translator->trans('param.site_url.label', [], 'admin.form'),
                'help'  => $this->translator->trans('param.site_url.help', [], 'admin.form'),
            ],
        ];
        foreach ($inputs as $key => $row) {
            $builder->add(
                $key,
                $row['class'],
                [
                    'label' => $row['label'],
                    'help'  => $row['help'],
                ]
            );
        }
    }

    private function setFileType($builder)
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
            $builder->add(
                $key,
                FileType::class,
                [
                    'label'    => $row['label'],
                    'help'     => $row['help'],
                    'required' => false,
                    'attr'     => ['accept' => 'image/*'],
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }
}
