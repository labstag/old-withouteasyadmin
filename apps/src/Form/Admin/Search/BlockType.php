<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\BlockSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends SearchAbstractTypeLib
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
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => BlockSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
