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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
                'label' => 'admin.form.user.username.label',
                'help'  => 'admin.form.user.username.help',
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
                    'label' => 'admin.form.user.password.label',
                    'help'  => 'admin.form.user.password.help',
                ],
                'second_options'  => [
                    'label' => 'admin.form.user.repeatpassword.label',
                    'help'  => 'admin.form.user.repeatpassword.help',
                ],
            ]
        );
        $builder->add(
            'refgroupe',
            SearchableType::class,
            [
                'label'    => 'admin.form.user.refgroupe.label',
                'help'     => 'admin.form.user.refgroupe.help',
                'multiple' => false,
                'class'    => Groupe::class,
                'route'    => 'api_search_group',
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => 'admin.form.user.file.label',
                'help'     => 'admin.form.user.file.help',
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
                        'label'   => 'admin.form.user.email.label',
                        'help'    => 'admin.form.user.email.help',
                        'choices' => $emails,
                    ]
                );
            }
        }

        $builder->add(
            'emailUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => EmailType::class,
            ]
        );
        $builder->add(
            'phoneUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => PhoneType::class,
            ]
        );
        $builder->add(
            'adresseUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => AdresseType::class,
            ]
        );
        $builder->add(
            'lienUsers',
            MinMaxCollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => LienType::class,
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
