<?php

namespace Labstag\Form\Admin\Search;

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
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->addName($builder);
        $this->addRefUser($builder);
        $this->addPublished($builder);
        $this->showState(
            $builder,
            new History(),
            $this->translator->trans('history.etape.label', [], 'admin.search.form'),
            $this->translator->trans('history.etape.help', [], 'admin.search.form'),
            $this->translator->trans('history.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => HistorySearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
