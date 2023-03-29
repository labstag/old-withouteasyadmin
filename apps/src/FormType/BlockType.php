<?php

namespace Labstag\FormType;

use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        unset($formView, $form, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'placeholder' => 'Choisir le block',
                'choices'     => $this->blockService->getAll(),
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
