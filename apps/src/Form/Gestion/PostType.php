<?php

namespace Labstag\Form\Gestion;

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
    ): void
    {
        $this->setTextType($formBuilder);
        $this->addPublished($formBuilder);
        $this->setContent($formBuilder);
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_post_paragraph_add',
                'edit'   => 'gestion_post_paragraph_show',
                'delete' => 'gestion_post_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('post.file.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('post.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('post.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('post.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.refuser.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'refcategory',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('post.refcategory.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('post.refcategory.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.refcategory.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'remark',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('post.remark.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('post.remark.help', [], 'gestion.form'),
            ]
        );
        $formBuilder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => $this->translator->trans('post.libelles.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('post.libelles.help', [], 'gestion.form'),
                'class' => Libelle::class,
                'new'   => new Libelle(),
                'add'   => true,
                'route' => 'api_search_postlibelle',
                'attr'  => [
                    'placeholder' => $this->translator->trans('post.libelles.placeholder', [], 'gestion.form'),
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
                'label' => $this->translator->trans('post.title.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('post.title.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('post.title.placeholder', [], 'gestion.form'),
                ],
            ],
            'slug'  => [
                'label'    => $this->translator->trans('post.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('post.slug.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.slug.placeholder', [], 'gestion.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
