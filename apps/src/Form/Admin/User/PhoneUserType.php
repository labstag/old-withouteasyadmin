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
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'principal',
            CheckboxType::class,
            [
                'label' => 'admin.form.phoneuser.principal.label',
                'help' => 'admin.form.phoneuser.principal.help',
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label' => 'admin.form.phoneuser.refuser.label',
                'help' => 'admin.form.phoneuser.refuser.help',
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => PhoneUser::class,
            ]
        );
    }
}
