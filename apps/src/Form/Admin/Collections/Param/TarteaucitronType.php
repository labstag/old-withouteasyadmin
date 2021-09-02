<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\FormType\CoreTextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarteaucitronType extends AbstractType
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
                'label' => 'admin.param.param.tarteaucitron.privacyUrl.label',
                'help' => 'admin.param.param.tarteaucitron.privacyUrl.help',
                'required' => false,
            ]
        );
        $builder->add(
            'hashtag',
            TextType::class,
            [
                'label' => 'admin.param.param.tarteaucitron.hashtag.label',
                'help' => 'admin.param.param.tarteaucitron.hashtag.help',
            ]
        );
        $builder->add(
            'cookieName',
            TextType::class,
            [
                'label' => 'admin.param.param.tarteaucitron.cookieName.label',
                'help' => 'admin.param.param.tarteaucitron.cookieName.help',
            ]
        );
        $builder->add(
            'orientation',
            TextType::class,
            [
                'label' => 'admin.param.param.tarteaucitron.orientation.label',
                'help' => 'admin.param.param.tarteaucitron.orientation.help',
            ]
        );
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
                'label' => 'admin.form.param.tarteaucitron.iconPosition.label',
                'help' => 'admin.form.param.tarteaucitron.iconPosition.help',
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
                'label' => 'admin.form.param.tarteaucitron.readmoreLink.label',
                'help' => 'admin.form.param.tarteaucitron.readmoreLink.help',
                'required' => false,
            ]
        );
        $builder->add(
            'mandatory',
            ChoiceType::class,
            [
                'label' => 'admin.form.param.tarteaucitron.mandatory.label',
                'help' => 'admin.form.param.tarteaucitron.mandatory.help',
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
                'label' => 'admin.form.param.tarteaucitron.job.label',
                'help' => 'admin.form.param.tarteaucitron.job.help',
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
                    'label' => 'admin.form.param.tarteaucitron.'.$id.'.label',
                    'help' => 'admin.form.param.tarteaucitron.'.$id.'.help',
                    'choices' => [
                        'Non' => '0',
                        'Oui' => '1',
                    ],
                ]
            );
        }
    }
}
