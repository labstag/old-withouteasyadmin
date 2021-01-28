<?php

namespace Labstag\Lib;

use Labstag\Service\BreadcrumbsService;
use Labstag\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ControllerLib extends AbstractController
{

    protected DataService $dataService;

    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        DataService $dataService,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->dataService = $dataService;
        $this->breadcrumbs = $breadcrumbs;
    }

    protected function setBreadcrumbs(): void
    {
        $data = BreadcrumbsService::getInstance()->get();
        foreach ($data as $title => $route) {
            $this->breadcrumbs->addItem($title, $route);
        }
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $this->setBreadcrumbs();
        if (isset($this->headerTitle) && $this->headerTitle != '') {
            $parameters['headerTitle'] = $this->headerTitle;
        }

        return parent::render($view, $parameters, $response);
    }
}
