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
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $label = $this->paragraphService->getName($formBuilder->getData());
        $formType = $this->paragraphService->getTypeForm($formBuilder->getData());
        $field = $this->paragraphService->getEntityField($formBuilder->getData());
        $show = $this->paragraphService->isShow($formBuilder->getData());
        $formBuilder->add('background');
        $formBuilder->add('color');
        if ((!is_null($formType) || is_null($field)) && $show) {
            $formBuilder->add(
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
