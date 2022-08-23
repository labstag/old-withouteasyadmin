<?php

namespace Labstag\Lib;

use Labstag\Service\BlockService;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        TemplatePageService $templatePageService,
        RouterInterface $router,
        protected BlockService $blockService,
        protected Environment $twig
    )
    {
        parent::__construct($translator, $templatePageService, $router);
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
