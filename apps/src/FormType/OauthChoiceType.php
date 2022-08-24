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
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $entity  = $form->getParent()->getData();
        $types   = $this->oauthService->getTypes();
        $choices = [];
        foreach ($types as $type) {
            $choices[$type] = new ChoiceView('', $type, $type);
        }

        ksort($choices);
        if (isset($entity['type'])) {
            $view->vars['value'] = $entity['type'];
        }

        $view->vars['choices'] = $choices;
        unset($options, $form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
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
