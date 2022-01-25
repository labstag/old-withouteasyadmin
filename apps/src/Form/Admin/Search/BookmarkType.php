<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\BookmarkSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookmarkType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->addName(
            $builder,
            $this->translator->trans('bookmark.name.label', [], 'admin.search.form'),
            $this->translator->trans('bookmark.name.help', [], 'admin.search.form'),
            $this->translator->trans('bookmark.name.placeholder', [], 'admin.search.form')
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('bookmark.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('bookmark.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('bookmark.refuser.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('bookmark.refcategory.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('bookmark.refcategory.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'bookmark.refcategory.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $this->showState(
            $builder,
            new Bookmark(),
            $this->translator->trans('bookmark.etape.label', [], 'admin.search.form'),
            $this->translator->trans('bookmark.etape.help', [], 'admin.search.form'),
            $this->translator->trans('bookmark.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => BookmarkSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
