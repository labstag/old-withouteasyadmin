<?php

namespace Labstag\Lib;

use Labstag\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ControllerLib extends AbstractController
{

    protected Breadcrumbs $breadcrumbs;

    protected array $arrayBreadcrumbs = [];

    protected DataService $dataService;

    public function __construct(
        DataService $dataService,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->dataService = $dataService;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function setBreadcrumbs(array $breadcrumbs): void
    {
        $this->arrayBreadcrumbs = $breadcrumbs;
    }

    public function getBreadcrumbs(): array
    {
        return $this->arrayBreadcrumbs;
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        foreach ($this->arrayBreadcrumbs as $title => $route) {
            $this->breadcrumbs->addItem($title, $route);
        }

        if (isset($this->headerTitle) && $this->headerTitle != '') {
            $parameters['headerTitle'] = $this->headerTitle;
        }

        return parent::render($view, $parameters, $response);
    }
}
