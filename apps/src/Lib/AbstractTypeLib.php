<?php

namespace Labstag\Lib;

use Labstag\FormType\MinMaxCollectionType;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTypeLib extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected TemplatePageService $templatePageService
    )
    {
    }

    protected function setCollectionType($builder, $tab)
    {
        foreach ($tab as $key => $type) {
            $builder->add(
                $key,
                MinMaxCollectionType::class,
                [
                    'label'        => ' ',
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'entry_type'   => $type,
                ]
            );
        }
    }

    protected function setInputText($builder, $tab)
    {
        foreach ($tab as $id => $row) {
            $builder->add(
                $id,
                TextType::class,
                [
                    'label' => $row['label'],
                    'help'  => $row['help'],
                    'attr'  => [
                        'placeholder' => $row['placeholder'],
                    ],
                ]
            );
        }
    }

    protected function setMetas($builder, $metas)
    {
        foreach ($metas as $key => $values) {
            $builder->add(
                $key,
                TextType::class,
                array_merge(
                    $values,
                    ['required' => false]
                )
            );
        }
    }
}
