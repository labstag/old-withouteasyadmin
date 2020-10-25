<?php

namespace Labstag\Form;

use Labstag\Entity\LienUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add('name');
        $builder->add('adresse');
        $builder->add('refuser');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => LienUser::class,
            ]
        );
    }
}
