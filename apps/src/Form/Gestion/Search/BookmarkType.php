<?php

namespace Labstag\Form\Gestion\Search;

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
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $this->addName($formBuilder);
        $this->addRefUser($formBuilder);
        $this->addRefCategory($formBuilder);
        $this->showState(
            $formBuilder,
            new Bookmark(),
            $this->translator->trans('bookmark.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('bookmark.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('bookmark.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => BookmarkSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
