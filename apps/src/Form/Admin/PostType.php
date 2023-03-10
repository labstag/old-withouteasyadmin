<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void {
        $this->setTextType($formBuilder);
        $this->addPublished($formBuilder);
        $this->setContent($formBuilder);
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'admin_post_paragraph_add',
                'edit'   => 'admin_post_paragraph_show',
                'delete' => 'admin_post_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('post.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
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
        $formBuilder->add(
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
        $formBuilder->add(
            'remark',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('post.remark.label', [], 'admin.form'),
                'help'  => $this->translator->trans('post.remark.help', [], 'admin.form'),
            ]
        );
        $formBuilder->add(
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
        $this->setMeta($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Post::class,
            ]
        );
    }

    protected function setTextType(FormBuilderInterface $formBuilder): void
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
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
