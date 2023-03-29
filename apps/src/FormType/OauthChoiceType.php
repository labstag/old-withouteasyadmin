<?php

namespace Labstag\FormType;

use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthChoiceType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        /** @var FormInterface $parent */
        $parent = $form->getParent();
        if (!$parent instanceof FormInterface) {
            return;
        }

        $entity = $parent->getData();

        $types   = $this->oauthService->getTypes();
        $choices = [];
        foreach ($types as $type) {
            $choices[$type] = new ChoiceView('', (string) $type, (string) $type);
        }

        ksort($choices);
        if (isset($entity['type'])) {
            $formView->vars['value'] = $entity['type'];
        }

        $formView->vars['choices'] = $choices;
        unset($options, $form);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            []
        );
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
