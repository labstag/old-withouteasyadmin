<?php

namespace Labstag\Form\Gestion\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrincipalType extends AbstractTypeLib
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
        $formBuilder->add(
            'url',
            UrlType::class,
            [
                'label'    => $this->translator->trans('bookmark.url.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('bookmark.url.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('bookmark.url.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $this->setContent($formBuilder);
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('bookmark.file.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('bookmark.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('bookmark.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('bookmark.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('bookmark.refuser.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'refcategory',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('bookmark.refcategory.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('bookmark.refcategory.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'bookmark.refcategory.placeholder',
                        [],
                        'gestion.form'
                    ),
                ],
            ]
        );
        $formBuilder->add(
            'libelles',
            SearchableType::class,
            [
                'label' => $this->translator->trans('bookmark.libelles.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('bookmark.libelles.help', [], 'gestion.form'),
                'class' => Libelle::class,
                'new'   => new Libelle(),
                'add'   => true,
                'route' => 'api_search_postlibelle',
                'attr'  => [
                    'placeholder' => $this->translator->trans('bookmark.libelles.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Bookmark::class,
            ]
        );
    }

    protected function setTextType(FormBuilderInterface $formBuilder): void
    {
        $texttype = [
            'name' => [
                'label' => $this->translator->trans('bookmark.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('bookmark.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('bookmark.name.placeholder', [], 'gestion.form'),
                ],
            ],
            'slug' => [
                'label'    => $this->translator->trans('bookmark.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('bookmark.slug.help', [], 'gestion.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('bookmark.slug.placeholder', [], 'gestion.form'),
                ],
                'required' => false,
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
