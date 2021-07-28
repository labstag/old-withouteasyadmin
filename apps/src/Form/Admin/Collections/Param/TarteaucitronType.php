<?php

namespace Labstag\Form\Admin\Collections\Param;

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
                'required' => false,
                'help'     => 'Privacy policy url',
            ]
        );
        $builder->add(
            'hashtag',
            TextType::class,
            ['help' => 'Open the panel with this hashtag']
        );
        $builder->add(
            'cookieName',
            TextType::class,
            ['help' => 'Cookie name']
        );
        $builder->add(
            'orientation',
            TextType::class,
            ['help' => 'Banner position (top - bottom)']
        );
        $tab = [
            'groupServices'  => 'Group services by category',
            'showAlertSmall' => 'Show the small banner on bottom right',
            'cookieslist'    => 'Show the cookie list',
            'closePopup'     => 'Show a close X on the banner',
            'showIcon'       => 'Show cookie icon to manage cookies',
        ];
        $this->setInputTrueFalse($builder, $tab);
        $builder->add(
            'iconPosition',
            ChoiceType::class,
            [
                'choices' => [
                    'BottomRight' => 'BottomRight',
                    'BottomLeft'  => 'BottomLeft',
                    'TopRight'    => 'TopRight',
                    'TopLeft'     => 'TopLeft',
                ],
            ]
        );
        $tab = [
            'adblocker'               => 'Show a Warning if an adblocker is detected',
            'DenyAllCta'              => 'Show the deny all button',
            'AcceptAllCta'            => 'Show the accept all button when highPrivacy on',
            'highPrivacy'             => 'HIGHLY RECOMMANDED Disable auto consent',
            'handleBrowserDNTRequest' => 'If Do Not Track == 1, disallow all',
            'removeCredit'            => 'Remove credit link',
            'moreInfoLink'            => 'Show more info link',
        ];
        $this->setInputTrueFalse($builder, $tab);
        $builder->add(
            'readmoreLink',
            UrlType::class,
            [
                'help'     => 'Change the default readmore link',
                'required' => false,
            ]
        );
        $builder->add(
            'mandatory',
            ChoiceType::class,
            [
                'help'    => 'Show a message about mandatory cookies',
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
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

    private function setInputTrueFalse($builder, $tab)
    {
        foreach ($tab as $id => $help) {
            $builder->add(
                $id,
                ChoiceType::class,
                [
                    'help'    => $help,
                    'choices' => [
                        'Non' => '0',
                        'Oui' => '1',
                    ],
                ]
            );
        }
    }
}
