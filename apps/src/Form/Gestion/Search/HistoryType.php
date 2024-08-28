<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Entity\History;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\HistorySearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends SearchAbstractTypeLib
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
        $this->addPublished($formBuilder);
        $this->showState(
            $formBuilder,
            new History(),
            $this->translator->trans('history.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('history.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('history.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => HistorySearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
