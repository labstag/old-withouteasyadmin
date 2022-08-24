<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $this->blockService->getTypeForm($builder->getData());
        $field    = $this->blockService->getEntityField($builder->getData());
        $builder->add('code');
        if (!is_null($formType) || is_null($field)) {
            $builder->add(
                $field,
                CollectionType::class,
                [
                    'label'         => ' ',
                    'entry_type'    => $formType,
                    'entry_options' => ['label' => false],
                    'allow_add'     => false,
                    'allow_delete'  => false,
                ]
            );
        }

        $builder->add('Enregistrer', SubmitType::class);
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
