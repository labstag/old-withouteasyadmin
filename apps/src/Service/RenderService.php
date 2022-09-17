<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;

class RenderService
{
    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    public function getAllFrontRoutes()
    {
        $routeCollection = $this->router->getRouteCollection();

        return $routeCollection->all();
    }
}
