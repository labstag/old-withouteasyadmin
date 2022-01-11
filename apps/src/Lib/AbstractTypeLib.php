<?php

namespace Labstag\Lib;

use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTypeLib extends AbstractType
{

    protected TemplatePageService $templatePageService;

    protected TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator,
        TemplatePageService $templatePageService
    )
    {
        $this->templatePageService = $templatePageService;
        $this->translator          = $translator;
    }
}
