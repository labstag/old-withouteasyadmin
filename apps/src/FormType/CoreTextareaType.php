<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CoreTextareaType extends AbstractType
{
    public final const ROWS = 20;

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $attr               = $options['attr'];
        $attr['rows']     ??= self::ROWS;
        $view->vars['attr'] = $attr;
        unset($form);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return TextareaType::class;
    }
}
