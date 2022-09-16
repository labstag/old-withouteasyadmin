<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;

class RenderService
{
    public function __construct(
        protected RouterInterface $routerInterface
    )
    {
    }

    public function getAllFrontRoutes()
    {
        $collection = $this->routerInterface->getRouteCollection();

        return $collection->all();
    }
}
