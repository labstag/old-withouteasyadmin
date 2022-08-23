<?php

namespace Labstag\Lib;

use Labstag\Service\FormService;
use Labstag\Service\ParagraphService;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ParagraphAbstractTypeLib extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        TemplatePageService $templatePageService,
        RouterInterface $router,
        protected ParagraphService $paragraphService,
        protected Environment $twig,
        protected FormService $formService
    )
    {
        parent::__construct($translator, $templatePageService, $router);
    }

    public function getFieldEntity()
    {
        return '';
    }

    public function getForm()
    {
        $forms = $this->formService->all();
        $data  = [];
        foreach ($forms as $form) {
            $name        = $form->getName();
            $data[$name] = $name;
        }

        ksort($data);

        return $data;
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
