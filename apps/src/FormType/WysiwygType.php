<?php

namespace Labstag\FormType;

use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class WysiwygType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $attr = $options['attr'];
        if (!is_array($attr)) {
            $attr = [];
        }

        if (!isset($attr['class'])) {
            $attr['class'] = '';
        }

        if (!is_string($attr['class'])) {
            return;
        }

        $attr['class'] = trim($attr['class'].' wysiwyg');

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
