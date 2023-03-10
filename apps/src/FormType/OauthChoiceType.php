<?php

namespace Labstag\FormType;

use Labstag\Service\OauthService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthChoiceType extends AbstractType
{
    public function __construct(
        protected OauthService $oauthService
    )
    {
    }

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        /** @var FormInterface $parent */
        $parent  = $form->getParent();
        $entity  = $parent->getData();
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
