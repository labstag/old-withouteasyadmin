<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    ): void {
        parent::buildForm($builder, $options);
        $choices = [];
        if ($options['data']->getRefUser() instanceof User) {
            $choices = [$options['data']->getRefUser()];
        }

        $builder->add(
            'refuser',
            EntityType::class,
            [
                'attr'    => ['is' => 'select-refuser'],
                'class'   => User::class,
                'choices' => $choices,
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
