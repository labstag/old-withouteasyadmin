<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AddressType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LinkType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\EmailVerifChoiceType;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'username',
            TextType::class,
            [
                'label' => $this->translator->trans('user.username.label', [], 'admin.form'),
                'help'  => $this->translator->trans('user.username.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('user.username.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $this->addPlainPassword($formBuilder);
        $formBuilder->add(
            'refgroupe',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('user.refgroupe.label', [], 'admin.form'),
                'help'     => $this->translator->trans('user.refgroupe.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Groupe::class,
                'route'    => 'api_search_group',
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.refgroupe.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'file',
            FileType::class,
            [
                'label'    => ' ',
                'help'     => $this->translator->trans('user.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'email',
            EmailVerifChoiceType::class
        );

        $this->setCollectionTypeAll($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

    protected function setCollectionTypeAll(FormBuilderInterface $formBuilder): void
    {
        $tab = [
            'emailUsers'   => EmailType::class,
            'phoneUsers'   => PhoneType::class,
            'addressUsers' => AddressType::class,
            'linkUsers'    => LinkType::class,
        ];
        $this->setCollectionType($formBuilder, $tab);
    }
}
