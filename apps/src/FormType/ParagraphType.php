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
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $entity     = $form->getParent()->getData();
        $paragraphs = $this->paragraphService->getAll($entity);
        $choices    = [];
        foreach ($paragraphs as $name => $type) {
            $choices[$type] = new ChoiceView('', $type, $name);
        }

        $view->vars['label']      = 'Paragraphs';
        $view->vars['urlAdd']     = $this->router->generate($options['add'], ['id' => $entity->getId()]);
        $view->vars['paragraphs'] = $entity->getParagraphs();
        $view->vars['urlEdit']    = $options['edit'];
        $view->vars['urlDelete']  = $options['delete'];
        $view->vars['choices']    = $choices;
        $view->vars['attr']['is'] = 'select-paragraph';
        unset($form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
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
