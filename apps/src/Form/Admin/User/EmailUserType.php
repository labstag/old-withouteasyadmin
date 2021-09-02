<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\EmailType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailUserType extends EmailType
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
                'label' => 'admin.form.emailuser.principal.label',
                'help'  => 'admin.form.emailuser.principal.help',
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => 'admin.form.emailuser.refuser.label',
                'help'     => 'admin.form.emailuser.refuser.help',
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
                'data_class' => EmailUser::class,
            ]
        );
    }
}
