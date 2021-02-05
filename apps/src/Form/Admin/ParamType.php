<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Collections\Param\DisclaimerType;
use Labstag\Form\Admin\Collections\Param\MetaSiteType;
use Labstag\Form\Admin\Collections\Param\NotificationType;
use Labstag\Form\Admin\Collections\Param\OauthType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
    ): void {
        $builder->add('site_title', TextType::class);
        $builder->add('robotstxt', TextareaType::class);
        $builder->add('languagedefault', LanguageType::class);
        $builder->add('site_no-reply', EmailType::class);
        $builder->add('site_url', UrlType::class);
        $builder->add(
            'language',
            LanguageType::class,
            ['multiple' => true]
        );
        $builder->add(
            'notification',
            MinMaxCollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => NotificationType::class,
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
        $builder->add(
            'meta',
            MinMaxCollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => MetaSiteType::class,
            ]
        );
        $builder->add(
            'disclaimer',
            MinMaxCollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => DisclaimerType::class,
            ]
        );
        $builder->add('site_copyright', WysiwygType::class);
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
