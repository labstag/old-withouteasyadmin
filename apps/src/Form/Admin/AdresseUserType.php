<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\AdresseUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    ): void
    {
        unset($options);
        $builder->add('rue');
        $builder->add('country');
        $builder->add('zipcode');
        $builder->add('ville');
        $builder->add('gps');
        $builder->add('type');
        $builder->add('pmr');
        $builder->add('refuser');
        $builder->add('submit', SubmitType::class);
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
