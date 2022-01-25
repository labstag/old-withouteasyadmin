<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AddressType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LinkType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\EmailUserRepository;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfilType extends AbstractTypeLib
{
    public function __construct(
        protected EmailUserRepository $repository,
        TranslatorInterface $translator,
        TemplatePageService $templatePageService
    )
    {
        parent::__construct($translator, $templatePageService);
    }

    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => $this->translator->trans('profil.username.label', [], 'admin.form'),
                'help'  => $this->translator->trans('profil.username.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('profil.username.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $this->addPlainPassword($builder);
        $this->addEmails($builder, $options, $this->repository);

        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => ' ',
                'help'     => $this->translator->trans('profil.file.help', [], 'admin.form'),
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
        $this->setCollectionType($builder, $tab);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
