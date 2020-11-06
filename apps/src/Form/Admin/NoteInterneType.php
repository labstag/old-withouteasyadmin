<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\NoteInterne;
use Labstag\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    ): void
    {
        unset($options);
        $builder->add(
            'title',
            TextType::class,
            ['required' => false]
        );
        $builder->add('content', WysiwygType::class);
        $builder->add('enable');
        $builder->add(
            'date_debut',
            DateTimeType::class,
            [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ]
        );
        $builder->add(
            'date_fin',
            DateTimeType::class,
            [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ]
        );
        $builder->add('submit', SubmitType::class);
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
