<?php

namespace Labstag\Form\Admin\Post;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractTypeLib
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
                'label' => $this->translator->trans('post.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.title.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('post.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.slug.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('post.published.label', [], 'admin.form'),
                'help'         => $this->translator->trans('post.published.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('post.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.content.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'metaDescription',
            TextType::class,
            [
                'label' => $this->translator->trans('post.metaDescription.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.metaDescription.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'metaKeywords',
            TextType::class,
            [
                'label' => $this->translator->trans('post.metaKeywords.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.metaKeywords.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('post.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('post.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        $builder->add(
            'commentaire',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('post.commentaire.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.commentaire.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => $this->translator->trans('post.libelles.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.libelles.help', [], 'admin.form'),
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
