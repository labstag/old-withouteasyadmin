<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\AdresseUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add('rue');
        $builder->add('country');
        $builder->add('zipcode');
        $builder->add('ville');
        $builder->add('gps');
        $builder->add('type');
        $builder->add('pmr');
        $builder->add('refuser');
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
