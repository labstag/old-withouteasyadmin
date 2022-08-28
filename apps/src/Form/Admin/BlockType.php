<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'label' => $this->translator->trans('block.html.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('block.html.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('block.html.title.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'region',
            ChoiceType::class,
            [
                'label' => $this->translator->trans('block.region.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('block.region.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('block.region.title.placeholder', [], 'admin.form'),
                ],
                'choices' => [
                    'header' => 'header',
                    'content' => 'content',
                    'footer' => 'footer',
                ]
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
