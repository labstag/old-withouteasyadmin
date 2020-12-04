<?php

namespace Labstag\Lib;

use Labstag\Service\AdminBoutonService;
use Labstag\Service\AdminCrudService;
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

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $router           = $this->get('router');
        $arrayBreadcrumbs = $this->getBreadcrumbs();
        $adminBreadcrumbs = [
            'Home' => $router->generate('admin'),
        ];
        if ($this->headerTitle != '' && $this->urlHome != '') {
            $adminBreadcrumbs[$this->headerTitle] = $router->generate(
                $this->urlHome
            );
        }

        $arrayBreadcrumbs = array_merge(
            $adminBreadcrumbs,
            $arrayBreadcrumbs
        );
        $this->setBreadcrumbs($arrayBreadcrumbs);
        $parameters = array_merge(
            $parameters,
            [
                'btnadmin' => $this->adminBoutonService->get(),
            ]
        );

        return parent::render($view, $parameters, $response);
    }
}
