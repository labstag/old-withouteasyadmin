<?php

namespace Labstag\Lib;

use Labstag\FormType\MinMaxCollectionType;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTypeLib extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected TemplatePageService $templatePageService
    )
    {
    }

    protected function addPublished($builder)
    {
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('published.label', [], 'admin.form'),
                'help'         => $this->translator->trans('published.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
    }

    protected function addEmails($builder, $options, $repository)
    {
        if (!(isset($options['data']) && !is_null($options['data']->getId()))) {
            return;
        }

        $emails = [];
        $data   = $repository->getEmailsUserVerif(
            $options['data'],
            true
        );
        foreach ($data as $email) {
            // @var EmailUser $email
            $address          = $email->getAddress();
            $emails[$address] = $address;
        }

        ksort($emails);

        if (0 == count($emails)) {
            return;
        }

        $builder->add(
            'email',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('email.label', [], 'admin.form'),
                'help'    => $this->translator->trans('email.help', [], 'admin.form'),
                'choices' => $emails,
                'attr'    => [
                    'placeholder' => $this->translator->trans('email.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    protected function addPlainPassword($builder)
    {
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
    }

    protected function setCollectionType($builder, $tab)
    {
        foreach ($tab as $key => $type) {
            $builder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'label'        => ' ',
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'entry_type'   => $type,
                ]
            );
        }
    }

    protected function setInputText($builder, $tab)
    {
        foreach ($tab as $id => $row) {
            $builder->add(
                $id,
                TextType::class,
                [
                    'label' => $row['label'],
                    'help'  => $row['help'],
                    'attr'  => [
                        'placeholder' => $row['placeholder'],
                    ],
                ]
            );
        }
    }

    protected function setMetas($builder, $metas)
    {
        foreach ($metas as $key => $values) {
            $builder->add(
                $key,
                TextType::class,
                array_merge(
                    $values,
                    ['required' => false]
                )
            );
        }
    }
}
