<?php

namespace Labstag\FormType;

use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class MinMaxCollectionType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void {
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
