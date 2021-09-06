<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteInterneType extends AbstractTypeLib
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
                'label' => $this->translator->trans('noteinterne.title.label', [], 'form'),
                'help'  => $this->translator->trans('noteinterne.title.help', [], 'form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label' => $this->translator->trans('noteinterne.slug.label', [], 'form'),
                'help'  => $this->translator->trans('noteinterne.slug.help', [], 'form'),
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('noteinterne.content.label', [], 'form'),
                'help'  => $this->translator->trans('noteinterne.content.help', [], 'form'),
            ]
        );
        $builder->add(
            'date_debut',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('noteinterne.date_debut.label', [], 'form'),
                'help'         => $this->translator->trans('noteinterne.date_debut.help', [], 'form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'date_fin',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('noteinterne.date_fin.label', [], 'form'),
                'help'         => $this->translator->trans('noteinterne.date_fin.help', [], 'form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );

        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('noteinterne.file.label', [], 'form'),
                'help'     => $this->translator->trans('noteinterne.file.help', [], 'form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('noteinterne.refuser.label', [], 'form'),
                'help'     => $this->translator->trans('noteinterne.refuser.help', [], 'form'),
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
