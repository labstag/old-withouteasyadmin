<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AddressType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LienType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\EmailUserRepository;
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

    protected EmailUserRepository $repository;

    public function __construct(
        EmailUserRepository $repository,
        TranslatorInterface $translator
    )
    {
        $this->repository = $repository;
        parent::__construct($translator);
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
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => $this->translator->trans('profil.password.match', [], 'admin.form'),
                'options'         => [
                    'attr' => ['class' => 'password-field'],
                ],
                'required'        => false,
                'first_options'   => [
                    'label' => $this->translator->trans('profil.password.label', [], 'admin.form'),
                    'help'  => $this->translator->trans('profil.password.help', [], 'admin.form'),
                ],
                'second_options'  => [
                    'label' => $this->translator->trans('profil.repeatpassword.label', [], 'admin.form'),
                    'help'  => $this->translator->trans('profil.repeatpassword.help', [], 'admin.form'),
                ],
            ]
        );
        if (isset($options['data']) && !is_null($options['data']->getId())) {
            $emails = [];
            $data   = $this->repository->getEmailsUserVerif(
                $options['data'],
                true
            );
            foreach ($data as $email) {
                $address          = $email->getAddress();
                $emails[$address] = $address;
            }

            ksort($emails);

            if (0 != count($emails)) {
                $builder->add(
                    'email',
                    ChoiceType::class,
                    [
                        'label'   => $this->translator->trans('profil.email.label', [], 'admin.form'),
                        'help'    => $this->translator->trans('profil.email.help', [], 'admin.form'),
                        'choices' => $emails,
                        'attr'    => [
                            'placeholder' => $this->translator->trans('profil.email.placeholder', [], 'admin.form'),
                        ],
                    ]
                );
            }
        }

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
            'lienUsers'    => LienType::class,
        ];

        foreach ($tab as $key => $type) {
            $builder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'label'        => ' ',
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'entry_type'   => $type,
                    'by_reference' => false,
                ]
            );
        }
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
