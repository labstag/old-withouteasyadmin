<?php

namespace Labstag\FormType;

use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailVerifChoiceType extends AbstractType
{
    public function __construct(
        protected TemplatePageService $templatePageService
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
        $choices = $this->templatePageService->getChoices();
        $resolver->setDefaults(
            ['choices' => $choices]
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
