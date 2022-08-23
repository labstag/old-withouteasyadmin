<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class OauthType extends AbstractType
{
    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $attr               = $options['attr'];
        $view->vars['attr'] = $attr;
        unset($form);
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
