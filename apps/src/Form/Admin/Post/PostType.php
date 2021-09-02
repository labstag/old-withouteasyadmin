<?php

namespace Labstag\Form\Admin\Post;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
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
                'label' => 'admin.form.post.title.label',
                'help'  => 'admin.form.post.title.help',
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => 'admin.form.post.slug.label',
                'help'     => 'admin.form.post.slug.help',
                'required' => false,
            ]
        );
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => 'admin.form.post.published.label',
                'help'         => 'admin.form.post.published.help',
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => 'admin.form.post.content.label',
                'help'  => 'admin.form.post.content.help',
            ]
        );
        $builder->add(
            'metaDescription',
            TextType::class,
            [
                'label' => 'admin.form.post.metaDescription.label',
                'help'  => 'admin.form.post.metaDescription.help',
            ]
        );
        $builder->add(
            'metaKeywords',
            TextType::class,
            [
                'label' => 'admin.form.post.metaKeywords.label',
                'help'  => 'admin.form.post.metaKeywords.help',
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => 'admin.form.post.file.label',
                'help'     => 'admin.form.post.file.help',
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => 'admin.form.post.refuser.label',
                'help'     => 'admin.form.post.refuser.help',
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        $builder->add(
            'commentaire',
            CheckboxType::class,
            [
                'label' => 'admin.form.post.commentaire.label',
                'help'  => 'admin.form.post.commentaire.help',
            ]
        );
        $builder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => 'admin.form.post.libelles.label',
                'help'  => 'admin.form.post.libelles.help',
                'class' => Libelle::class,
                'route' => 'api_search_postlibelle',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Post::class,
            ]
        );
    }
}
