<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;

class RouteService
{
    public function __construct(
        protected RouterInterface $routerInterface
    )
    {
    }

    public function getAll()
    {
        $collection = $this->routerInterface->getRouteCollection();

        return $collection->all();
    }
}
