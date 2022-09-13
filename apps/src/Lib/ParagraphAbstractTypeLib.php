<?php

namespace Labstag\Lib;

use Labstag\Service\FormService;
use Labstag\Service\ParagraphService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ParagraphAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected RouterInterface $router,
        protected ParagraphService $paragraphService,
        protected Environment $twig,
        protected FormService $formService
    )
    {
        parent::__construct($translator);
    }

    protected function getRender($view, $param = [])
    {
        return $this->twig->render($view, $param);
    }
}
