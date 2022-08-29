<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $this->blockService->getTypeForm($builder->getData());
        $label    = $this->blockService->getName($builder->getData());
        $field    = $this->blockService->getEntityField($builder->getData());
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => $this->translator->trans('block.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('block.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('block.title.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'region',
            ChoiceType::class,
            [
                'label'   => $this->translator->trans('block.region.label', [], 'admin.form'),
                'help'    => $this->translator->trans('block.region.help', [], 'admin.form'),
                'attr'    => [
                    'placeholder' => $this->translator->trans('block.region.placeholder', [], 'admin.form'),
                ],
                'choices' => $this->blockService->getRegions(),
            ]
        );
        if (!is_null($formType) || is_null($field)) {
            $builder->add(
                $field,
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

        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Block::class,
            ]
        );
    }
}
