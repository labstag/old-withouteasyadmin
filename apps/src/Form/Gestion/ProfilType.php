<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\User;
use Labstag\Form\Gestion\Collections\User\AddressType;
use Labstag\Form\Gestion\Collections\User\EmailType;
use Labstag\Form\Gestion\Collections\User\LinkType;
use Labstag\Form\Gestion\Collections\User\PhoneType;
use Labstag\FormType\EmailVerifChoiceType;
use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'username',
            TextType::class,
            [
                'label' => $this->translator->trans('profil.username.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('profil.username.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('profil.username.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $this->addPlainPassword($formBuilder);
        $formBuilder->add(
            'email',
            EmailVerifChoiceType::class,
        );
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => ' ',
                'help'     => $this->translator->trans('profil.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );

        $tab = [
            'emailUsers'   => EmailType::class,
            'phoneUsers'   => PhoneType::class,
            'addressUsers' => AddressType::class,
            'linkUsers'    => LinkType::class,
        ];
        $this->setCollectionType($formBuilder, $tab);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
