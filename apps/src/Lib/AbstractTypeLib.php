<?php

namespace Labstag\Lib;

use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTypeLib extends AbstractType
{

    public function __construct(protected TranslatorInterface $translator, protected TemplatePageService $templatePageService)
    {
    }
}
