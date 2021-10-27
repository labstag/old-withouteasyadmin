<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FlagCountryType extends AbstractType
{
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $view->vars['attr']['is'] = 'select-country';

        unset($form, $options);
    }

    /**
     * @inheritdoc
     */
    public function getParent(): string
    {
        return CountryType::class;
    }
}
