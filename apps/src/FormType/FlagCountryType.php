<?php

namespace Labstag\FormType;

use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlagCountryType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $formView->vars['attr']['is'] = 'select-country';

        unset($form, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'label' => $this->translator->trans('forms.country.label', [], 'admin.form'),
                'help'  => $this->translator->trans('forms.country.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('forms.country.placeholder', [], 'admin.form'),
                    'is'          => 'select-country',
                    'choices'     => 'true',
                ],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return CountryType::class;
    }
}
