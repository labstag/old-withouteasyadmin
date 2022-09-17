<?php

namespace Labstag\Service;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class RouteService
{
    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    /**
     * @return array<string, Route>
     */
    public function getAll(): array
    {
        $routeCollection = $this->router->getRouteCollection();

        return $routeCollection->all();
    }
}
