<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class MinMaxCollectionType extends AbstractType
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $formView->vars['row_attr'] = 'minmax';
        unset($form, $options);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return CollectionType::class;
    }
}
