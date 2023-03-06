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
        protected Environment $twigEnvironment
    )
    {
        parent::__construct($translator);
    }

    public function getFieldEntity(): string
    {
        return '';
    }

    public function getFormType(): string
    {
        return '';
    }

    protected function getRender(string $view, array $param = []): string
    {
        return $this->twigEnvironment->render($view, $param);
    }
}
