<?php

namespace Labstag\Lib;

use Labstag\Service\AdminBoutonService;
use Labstag\Service\AdminCrudService;
use Labstag\Service\DataService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminControllerLib extends ControllerLib
{

    protected AdminBoutonService $adminBoutonService;

    protected AdminCrudService $adminCrudService;

    public function __construct(
        DataService $dataService,
        AdminBoutonService $adminBoutonService,
        AdminCrudService $adminCrudService
    )
    {
        $this->adminBoutonService = $adminBoutonService;
        $this->adminCrudService   = $adminCrudService;
        $this->adminCrudService->setController($this);
        parent::__construct($dataService);
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = array_merge(
            $parameters,
            [
                'btnadmin' => $this->adminBoutonService->get(),
            ]
        );

        return parent::render($view, $parameters, $response);
    }
}
