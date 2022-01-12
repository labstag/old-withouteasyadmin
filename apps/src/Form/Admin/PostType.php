<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\WysiwygType;
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
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->setTextType($builder);
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
            WysiwygType::class,
            [
                'label' => $this->translator->trans('post.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.content.help', [], 'admin.form'),
            ]
        );
        $this->setMeta($builder);
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
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.refuser.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('post.refcategory.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.refcategory.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.refcategory.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'remark',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('post.remark.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.remark.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => $this->translator->trans('post.libelles.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.libelles.help', [], 'admin.form'),
                'class' => Libelle::class,
                'new'   => new Libelle(),
                'add'   => true,
                'route' => 'api_search_postlibelle',
                'attr'  => [
                    'placeholder' => $this->translator->trans('post.libelles.placeholder', [], 'admin.form'),
                ],
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

    protected function setMeta($builder)
    {
        $meta = [
            'metaDescription' => [
                'label' => $this->translator->trans('post.metaDescription.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.metaDescription.help', [], 'admin.form'),
            ],
            'metaKeywords'    => [
                'label' => $this->translator->trans('post.metaKeywords.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.metaKeywords.help', [], 'admin.form'),
            ],
        ];

        foreach ($meta as $key => $values) {
            $builder->add(
                $key,
                TextType::class,
                array_merge(
                    $values,
                    ['required' => false]
                )
            );
        }
    }

    protected function setTextType($builder)
    {
        $texttype = [
            'title' => [
                'label' => $this->translator->trans('post.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('post.title.placeholder', [], 'admin.form'),
                ],
            ],
            'slug'  => [
                'label'    => $this->translator->trans('post.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.slug.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.slug.placeholder', [], 'admin.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $builder->add($key, TextType::class, $args);
        }
    }
}
