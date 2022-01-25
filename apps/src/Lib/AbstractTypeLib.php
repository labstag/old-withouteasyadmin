<?php

namespace Labstag\Lib;

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
