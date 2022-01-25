<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Form\Admin\Collections\User\AddressType;
use Labstag\Form\Admin\Collections\User\EmailType;
use Labstag\Form\Admin\Collections\User\LinkType;
use Labstag\Form\Admin\Collections\User\PhoneType;
use Labstag\FormType\MinMaxCollectionType;
use Labstag\FormType\SearchableType;
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

class UserType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected EmailUserRepository $repository,
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
                'label' => $this->translator->trans('user.username.label', [], 'admin.form'),
                'help'  => $this->translator->trans('user.username.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('user.username.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'invalid_message' => $this->translator->trans('profil.password.match', [], 'admin.form'),
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
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.refgroupe.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => ' ',
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
                // @var EmailUser $email
                $address          = $email->getAddress();
                $emails[$address] = $address;
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
                        'attr'    => [
                            'placeholder' => $this->translator->trans('user.email.placeholder', [], 'admin.form'),
                        ],
                    ]
                );
            }
        }

        $this->setCollectionTypeAll($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

    protected function setCollectionTypeAll(FormBuilderInterface $builder)
    {
        $tab = [
            'emailUsers'   => EmailType::class,
            'phoneUsers'   => PhoneType::class,
            'addressUsers' => AddressType::class,
            'linkUsers'    => LinkType::class,
        ];
        $this->setCollectionType($builder, $tab);
    }
}
