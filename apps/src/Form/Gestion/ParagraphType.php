<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Paragraph;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $paragraph = $formBuilder->getData();
        if (!$paragraph instanceof Paragraph) {
            return;
        }

        $label    = $this->paragraphService->getName($paragraph);
        $formType = $this->paragraphService->getTypeForm($paragraph);
        $field    = $this->paragraphService->getEntityField($paragraph);
        $show     = $this->paragraphService->isShow($paragraph);
        $formBuilder->add('background');
        $formBuilder->add('color');
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

        $formBuilder->add('Enregistrer', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Paragraph::class,
            ]
        );
    }
}
