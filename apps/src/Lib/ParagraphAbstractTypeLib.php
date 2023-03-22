<?php

namespace Labstag\Lib;

use Labstag\Service\FormService;
use Labstag\Service\GuardService;
use Labstag\Service\ParagraphService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ParagraphAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        GuardService $guardService,
        protected RouterInterface $router,
        protected ParagraphService $paragraphService,
        protected Environment $twigEnvironment,
        protected FormService $formService
    )
    {
        parent::__construct($translator, $guardService);
    }

    protected function getRender(
        string $view,
        array $param = []
    ): string
    {
        return $this->twigEnvironment->render($view, $param);
    }
}
