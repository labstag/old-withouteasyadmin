<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Bookmark;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\BookmarkSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookmarkType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->addName($builder);
        $this->addRefUser($builder);
        $this->addRefCategory($builder);
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
}
