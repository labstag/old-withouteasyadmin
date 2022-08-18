<?php

namespace Labstag\Lib;

use Labstag\Service\BlockService;
use Labstag\Service\TemplatePageService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        TemplatePageService $templatePageService,
        protected BlockService $blockService,
        protected Environment $twig
    )
    {
        parent::__construct($translator, $templatePageService);
    }

    public function getFieldEntity()
    {
        return '';
    }

    public function getFormType()
    {
        return '';
    }

    protected function getRender($view, $param = [])
    {
        return $this->twig->render($view, $param);
    }
}
