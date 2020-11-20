<?php

namespace Labstag\Lib;

use Labstag\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class ControllerLib extends AbstractController
{

    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
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
                'oauthActivated' => $this->dataService->getOauthActivated(),
                'config'         => $this->dataService->getConfig(),
            ]
        );

        return parent::render($view, $parameters, $response);
    }
}
