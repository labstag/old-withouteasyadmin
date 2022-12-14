<?php

namespace Labstag\Lib;

use Labstag\Service\BlockService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected RouterInterface $router,
        protected BlockService $blockService,
        protected Environment $environment
    )
    {
        parent::__construct($translator);
    }

    public function getFieldEntity()
    {
        return '';
    }

    public function getFormType()
    {
        return '';
    }

    protected function getRender($view, array $param = [])
    {
        return $this->environment->render($view, $param);
    }
}
