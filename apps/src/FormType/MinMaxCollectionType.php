<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class MinMaxCollectionType extends AbstractType
{
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $view->vars['row_attr'] = 'minmax';
        unset($form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return CollectionType::class;
    }
}
