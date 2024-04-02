<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Block;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $block = $formBuilder->getData();
        if (!$block instanceof Block) {
            return;
        }

        $formType = $this->blockService->getTypeForm($block);
        $label    = $this->blockService->getName($block);
        $field    = $this->blockService->getEntityField($block);
        $show     = $this->blockService->isShow($block);
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'label' => $this->translator->trans('block.title.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('block.title.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('block.title.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'region',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('block.region.label', [], 'gestion.form'),
                'help'    => $this->translator->trans('block.region.help', [], 'gestion.form'),
                'attr'    => [
                    'placeholder' => $this->translator->trans('block.region.placeholder', [], 'gestion.form'),
                ],
                'choices' => $this->blockService->getRegions(),
            ]
        );
        if ((!is_null($formType) || is_null($field)) && $show) {
            $formBuilder->add(
                (string) $field,
                CollectionType::class,
                [
                    'label'         => $label,
                    'entry_type'    => $formType,
                    'entry_options' => ['label' => false],
                    'allow_add'     => false,
                    'allow_delete'  => false,
                ]
            );
        }

        $formBuilder->add(
            child: 'groupes',
            options: [
                'label' => $this->translator->trans('block.groupes.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('block.groupes.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('block.groupes.placeholder', [], 'gestion.form'),
                ],
            ]
        );

        $formBuilder->add(
            'notinpages',
        );

        unset($options);
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
