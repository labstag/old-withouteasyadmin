<?php

namespace Labstag\FormType;

use Labstag\Service\ParagraphService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ParagraphType extends AbstractType
{
    public function __construct(
        protected ParagraphService $paragraphService,
        protected RouterInterface $router
    )
    {
    }

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $entity = $form->getParent()->getData();
        $paragraphs = $this->paragraphService->getAll($entity);
        $choices = [];
        foreach ($paragraphs as $name => $type) {
            $choices[$type] = new ChoiceView('', $type, $name);
        }

        $formView->vars['label'] = 'Paragraphs';
        if (!is_null($entity->getId())) {
            $formView->vars['urlAdd'] = $this->router->generate($options['add'], ['id' => $entity->getId()]);
        }

        $formView->vars['paragraphs'] = $entity->getParagraphs();
        $formView->vars['urlEdit'] = $options['edit'];
        $formView->vars['urlDelete'] = $options['delete'];
        $formView->vars['choices'] = $choices;
        $formView->vars['attr']['is'] = 'select-paragraph';
        unset($form);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'placeholder' => 'Choisir le paragraphe',
                'choices'     => [],
                'add'         => null,
                'edit'        => null,
                'delete'      => null,
            ]
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
