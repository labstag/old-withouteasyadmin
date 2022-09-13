<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $label    = $this->paragraphService->getName($builder->getData());
        $formType = $this->paragraphService->getTypeForm($builder->getData());
        $field    = $this->paragraphService->getEntityField($builder->getData());
        $show     = $this->paragraphService->isShow($builder->getData());
        $builder->add('background');
        $builder->add('color');
        if ((!is_null($formType) || is_null($field)) && $show) {
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

        $builder->add('Enregistrer', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Paragraph::class,
            ]
        );
    }
}