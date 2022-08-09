<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Paragraph;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Service\ParagraphService;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ParagraphType extends AbstractTypeLib
{
    public function __construct(
        protected ParagraphService $paragraphService,
        protected TranslatorInterface $translator,
        protected TemplatePageService $templatePageService
    )
    {
        parent::__construct($translator, $templatePageService);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $this->paragraphService->getTypeForm($options['data']);
        $field    = $this->paragraphService->getEntityField($options['data']);
        $builder->add('background');
        $builder->add('color');
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
