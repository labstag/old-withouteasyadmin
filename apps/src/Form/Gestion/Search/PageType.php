<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\PageSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends SearchAbstractTypeLib
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
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => PageSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
