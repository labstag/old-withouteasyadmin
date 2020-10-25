<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add('username');
        $builder->add('roles');
        $builder->add('password');
        $builder->add('nom');
        $builder->add('prenom');
        $builder->add('enable');
        $builder->add('groupe');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
