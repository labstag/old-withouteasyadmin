<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            [
                'label' => 'admin.form.noteinterne.title.label',
                'help'  => 'admin.form.noteinterne.title.help',
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label' => 'admin.form.noteinterne.slug.label',
                'help'  => 'admin.form.noteinterne.slug.help',
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => 'admin.form.noteinterne.content.label',
                'help'  => 'admin.form.noteinterne.content.help',
            ]
        );
        $builder->add(
            'date_debut',
            DateTimeType::class,
            [
                'label'        => 'admin.form.noteinterne.date_debut.label',
                'help'         => 'admin.form.noteinterne.date_debut.help',
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'date_fin',
            DateTimeType::class,
            [
                'label'        => 'admin.form.noteinterne.date_fin.label',
                'help'         => 'admin.form.noteinterne.date_fin.help',
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );

        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => 'admin.form.noteinterne.file.label',
                'help'     => 'admin.form.noteinterne.file.help',
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => 'admin.form.noteinterne.refuser.label',
                'help'     => 'admin.form.noteinterne.refuser.help',
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
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
