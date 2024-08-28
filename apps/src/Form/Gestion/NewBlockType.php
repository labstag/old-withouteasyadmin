<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Block;
use Labstag\FormType\BlockType;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewBlockType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        unset($options);
        $formBuilder->add(
            'region',
            ChoiceType::class,
            [
                'choices' => $this->blockService->getRegions(),
            ]
        );
        $formBuilder->add(
            'type',
            BlockType::class
        );
        $formBuilder->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Block::class,
            ]
        );
    }
}
