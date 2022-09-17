<?php

namespace Labstag\Service;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class RenderService
{
    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    /**
     * @return array<string, Route>
     */
    public function getAllFrontRoutes(): array
    {
        $routeCollection = $this->router->getRouteCollection();

        return $routeCollection->all();
    }
}
