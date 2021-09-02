<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AdresseType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LienType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\Repository\EmailUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
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
                'label' => 'admin.form.profil.username.label',
                'help' => 'admin.form.profil.username.help',
            ]
        );
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options'         => [
                    'attr' => ['class' => 'password-field'],
                ],
                'required'        => false,
                'first_options'   => [
                    'label' => 'admin.form.profil.password.label',
                    'help' => 'admin.form.profil.password.help',
                ],
                'second_options'  => [
                    'label' => 'admin.form.profil.repeatpassword.label',
                    'help' => 'admin.form.profil.repeatpassword.help',
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
                $adresse          = $email->getAdresse();
                $emails[$adresse] = $adresse;
            }

            ksort($emails);

            if (0 != count($emails)) {
                $builder->add(
                    'email',
                    ChoiceType::class,
                    [
                        'label' => 'admin.form.profil.email.label',
                        'help' => 'admin.form.profil.email.help',
                        'choices' => $emails
                    ]
                );
            }
        }

        $builder->add(
            'file',
            FileType::class,
            [
                'label' => 'admin.form.profil.file.label',
                'help' => 'admin.form.profil.file.help',
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );

        $builder->add(
            'emailUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => EmailType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'phoneUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => PhoneType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'adresseUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => AdresseType::class,
                'by_reference' => false,
            ]
        );
        $builder->add(
            'lienUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => LienType::class,
                'by_reference' => false,
            ]
        );
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
