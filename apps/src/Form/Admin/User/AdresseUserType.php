<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\AdresseType;
use Labstag\FormType\SelectRefUserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseUserType extends AdresseType
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
            'refuser',
            SelectRefUserType::class,
            [
                'class' => User::class,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AdresseUser::class,
            ]
        );
    }
}
