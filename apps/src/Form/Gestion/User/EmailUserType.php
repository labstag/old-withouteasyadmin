<?php

namespace Labstag\Form\Gestion\User;

use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Form\Gestion\EmailType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailUserType extends EmailType
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        parent::buildForm($formBuilder, $options);
        $formBuilder->add(
            'principal',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('emailuser.principal.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('emailuser.principal.help', [], 'gestion.form'),
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('emailuser.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('emailuser.refuser.help', [], 'gestion.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('emailuser.refuser.placeholder', [], 'gestion.form'),
                ],
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => EmailUser::class,
            ]
        );
    }
}
