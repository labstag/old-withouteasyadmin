<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\NoteInterne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteInterneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add('title');
        $builder->add('content');
        $builder->add('enable');
        $builder->add('date_debut');
        $builder->add('date_fin');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => NoteInterne::class,
            ]
        );
    }
}
