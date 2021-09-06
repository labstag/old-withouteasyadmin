<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AdresseType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LienType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\FormType\SearchableType;
use Labstag\Repository\EmailUserRepository;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractTypeLib
{

    protected EmailUserRepository $repository;

    public function __construct(EmailUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
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
                'label' => $this->translator->trans('user.username.label', [], 'admin.form'),
                'help'  => $this->translator->trans('user.username.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => false,
                'first_options'   => [
                    'label' => $this->translator->trans('user.password.label', [], 'admin.form'),
                    'help'  => $this->translator->trans('user.password.help', [], 'admin.form'),
                ],
                'second_options'  => [
                    'label' => $this->translator->trans('user.repeatpassword.label', [], 'admin.form'),
                    'help'  => $this->translator->trans('user.repeatpassword.help', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'refgroupe',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('user.refgroupe.label', [], 'admin.form'),
                'help'     => $this->translator->trans('user.refgroupe.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Groupe::class,
                'route'    => 'api_search_group',
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('user.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('user.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        if (isset($options['data']) && !is_null($options['data']->getId())) {
            $emails = [];
            $data   = $this->repository->getEmailsUserVerif(
                $options['data'],
                true
            );
            foreach ($data as $email) {
                /** @var EmailUser $email */
                $adresse          = $email->getAdresse();
                $emails[$adresse] = $adresse;
            }

            ksort($emails);

            if (0 != count($emails)) {
                $builder->add(
                    'email',
                    ChoiceType::class,
                    [
                        'label'   => $this->translator->trans('user.email.label', [], 'admin.form'),
                        'help'    => $this->translator->trans('user.email.help', [], 'admin.form'),
                        'choices' => $emails,
                    ]
                );
            }
        }

        $tab = [
            'emailUsers'   => EmailType::class,
            'phoneUsers'   => PhoneType::class,
            'adresseUsers' => AdresseType::class,
            'lienUsers'    => LienType::class,
        ];

        foreach ($tab as $key => $type) {
            $builder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'entry_type'   => $type,
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
