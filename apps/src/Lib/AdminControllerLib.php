<?php

namespace Labstag\Lib;

use Labstag\Service\AdminBoutonService;
use Labstag\Service\AdminCrudService;
use Labstag\Service\BreadcrumbsService;
use Labstag\Service\DataService;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class AdminControllerLib extends ControllerLib
{

    protected string $headerTitle = '';

    protected string $urlHome = '';

    protected AdminBoutonService $adminBoutonService;

    protected AdminCrudService $adminCrudService;

    public function __construct(
        DataService $dataService,
        AdminBoutonService $adminBoutonService,
        AdminCrudService $adminCrudService,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->adminBoutonService = $adminBoutonService;
        $this->adminCrudService   = $adminCrudService;
        $this->adminCrudService->setController($this);
        parent::__construct($dataService, $breadcrumbs);
    }

    private function setBreadcrumbsPage()
    {
        if ($this->headerTitle == '' && $this->urlHome == '') {
            return;
        }

        $router      = $this->get('router');
        $breadcrumbs = [
            $this->headerTitle => $router->generate(
                $this->urlHome
            ),
        ];

        BreadcrumbsService::getInstance()->addPosition($breadcrumbs, 0);
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $this->setBreadcrumbsPage();
        $parameters = array_merge(
            $parameters,
            [
                'btnadmin' => $this->adminBoutonService->get(),
            ]
        );

        return parent::render($view, $parameters, $response);
    }
}
