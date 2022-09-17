<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;

class RouteService
{
    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    public function getAll()
    {
        $routeCollection = $this->router->getRouteCollection();

        return $routeCollection->all();
    }
}
