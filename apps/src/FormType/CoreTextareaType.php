<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CoreTextareaType extends AbstractType
{
    /**
     * @var int
     */
    final public const ROWS = 20;

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $attr                   = $options['attr'];
        $attr['rows']         ??= self::ROWS;
        $formView->vars['attr'] = $attr;
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
