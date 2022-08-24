<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->setTextType($builder);
        $this->addPublished($builder);
        $this->setContent($builder);
        $this->addParagraph(
            $builder,
            [
                'add'    => 'admin_post_paragraph_add',
                'edit'   => 'admin_post_paragraph_show',
                'delete' => 'admin_post_paragraph_delete',
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
        $this->setMeta($builder);
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
