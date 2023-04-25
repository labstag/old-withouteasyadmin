<?php

namespace Labstag\Lib;

use Labstag\Service\BlockService;
use Labstag\Service\GuardService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        GuardService $guardService,
        protected RouterInterface $router,
        protected BlockService $blockService,
        protected Environment $twigEnvironment
    ) {
        parent::__construct($translator, $guardService);
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
