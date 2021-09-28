<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookmarkType extends AbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('bookmark.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('bookmark.title.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('bookmark.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.slug.help', [], 'admin.form'),
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
            'url',
            UrlType::class,
            [
                'label'    => $this->translator->trans('bookmark.url.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.url.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('bookmark.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('bookmark.content.help', [], 'admin.form'),
            ]
        );
        $this->setMeta($builder);
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('bookmark.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('bookmark.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('bookmark.refcategory.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.refcategory.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
            ]
        );
        $builder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => $this->translator->trans('bookmark.libelles.label', [], 'admin.form'),
                'help'  => $this->translator->trans('bookmark.libelles.help', [], 'admin.form'),
                'class' => Libelle::class,
                'new'   => new Libelle(),
                'add'   => true,
                'route' => 'api_search_postlibelle',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Bookmark::class,
            ]
        );
    }

    protected function setMeta($builder)
    {
        $meta = [
            'metaDescription' => [
                'label' => $this->translator->trans('bookmark.metaDescription.label', [], 'admin.form'),
                'help'  => $this->translator->trans('bookmark.metaDescription.help', [], 'admin.form'),
            ],
            'metaKeywords'    => [
                'label' => $this->translator->trans('bookmark.metaKeywords.label', [], 'admin.form'),
                'help'  => $this->translator->trans('bookmark.metaKeywords.help', [], 'admin.form'),
            ],
        ];

        foreach ($meta as $key => $values) {
            $builder->add($key, TextType::class, $values);
        }
    }
}
