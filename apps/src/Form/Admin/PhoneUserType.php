<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\PhoneUser;
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
    ): void {
        parent::buildForm($builder, $options);
        $builder->add('principal');
        $builder->add('refuser');
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
