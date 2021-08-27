<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\PhoneType;
use Labstag\FormType\SelectRefUserType;
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
        $builder->add('principal');
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
                'data_class' => PhoneUser::class,
            ]
        );
    }
}
