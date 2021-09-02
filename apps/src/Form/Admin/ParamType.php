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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add('site_title', TextType::class);
        $images = [
            'image',
            'favicon',
        ];
        foreach ($images as $key) {
            $builder->add(
                $key,
                FileType::class,
                [
                    'label' => 'admin.form.param.'.$key.'.label',
                    'help' => 'admin.form.param.'.$key.'.help',
                    'required' => false,
                    'attr'     => ['accept' => 'image/*'],
                ]
            );
        }

        $builder->add(
            'title_format',
            TextType::class,
            [
                'label' => 'admin.form.param.title_format.label',
                'help' => 'admin.form.param.title_format.help',
            ]
        );
        $builder->add(
            'robotstxt',
            TextareaType::class,
            [
                'label' => 'admin.form.param.robotstxt.label',
                'help' => 'admin.form.param.robotstxt.help',
            ]
        );
        $builder->add(
            'languagedefault',
            LanguageType::class,
            [
                'label' => 'admin.form.param.languagedefault.label',
                'help' => 'admin.form.param.languagedefault.help',
            ]
        );
        $builder->add(
            'site_no-reply',
            EmailType::class,
            [
                'label' => 'admin.form.param.site_no-reply.label',
                'help' => 'admin.form.param.site_no-reply.help',
            ]
        );
        $builder->add(
            'site_url',
            UrlType::class,
            [
                'label' => 'admin.form.param.site_url.label',
                'help' => 'admin.form.param.site_url.help',
            ]
        );
        $builder->add(
            'language',
            LanguageType::class,
            [
                'multiple' => true,
                'label' => 'admin.form.param.language.label',
                'help' => 'admin.form.param.language.help',
            ]
        );
        $builder->add(
            'generator',
            ChoiceType::class,
            [
                'label' => 'admin.form.param.generator.label',
                'help' => 'admin.form.param.generator.help',
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

        $builder->add(
            'site_copyright',
            CKEditorType::class,
            [
                'label' => 'admin.form.param.site_copyright.label',
                'help' => 'admin.form.param.site_copyright.help',
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
