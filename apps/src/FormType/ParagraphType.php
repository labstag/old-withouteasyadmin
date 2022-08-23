<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ParagraphType extends AbstractType
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
        $attr                     = $options['attr'];
        $attr['is']               = 'select-paragraph';
        $view->vars['label']      = 'Paragraphs';
        $view->vars['urlAdd']     = $this->router->generate($options['add'], ['id' => $options['data']->getId()]);
        $view->vars['paragraphs'] = $options['data']->getParagraphs();
        $view->vars['urlEdit']    = $options['edit'];
        $view->vars['urlDelete']  = $options['delete'];
        $view->vars['attr']       = $attr;
        unset($form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'placeholder' => 'Choisir le paragraphe',
                'choices'     => ['Texte' => 'text'],
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
