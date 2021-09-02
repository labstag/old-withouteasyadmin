<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'admin.form.edito.title.label',
                'help'  => 'admin.form.edito.title.help',
            ]
        );
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => 'admin.form.edito.published.label',
                'help'         => 'admin.form.edito.published.help',
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => 'admin.form.edito.content.label',
                'help'  => 'admin.form.edito.content.help',
            ]
        );
        $builder->add(
            'metaDescription',
            TextType::class,
            [
                'label' => 'admin.form.edito.metaDescription.label',
                'help'  => 'admin.form.edito.metaDescription.help',
            ]
        );
        $builder->add(
            'metaKeywords',
            TextType::class,
            [
                'label' => 'admin.form.edito.metaKeywords.label',
                'help'  => 'admin.form.edito.metaKeywords.help',
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => 'admin.form.edito.file.label',
                'help'     => 'admin.form.edito.file.help',
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => 'admin.form.edito.refuser.label',
                'help'     => 'admin.form.edito.refuser.help',
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Edito::class,
            ]
        );
    }
}
