<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\PhoneType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneUserType extends PhoneType
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
                'label' => $this->translator->trans('phoneuser.principal.label', [], 'admin.form'),
                'help'  => $this->translator->trans('phoneuser.principal.help', [], 'admin.form'),
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('phoneuser.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('phoneuser.refuser.help', [], 'admin.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('phoneuser.refuser.placeholder', [], 'admin.form'),
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
                'data_class' => PhoneUser::class,
            ]
        );
    }
}
