<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlagCountryType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator
    )
    {
    }

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
