<?php

namespace Labstag\Lib;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;

abstract class OpenApiLib implements OpenApiFactoryInterface
{
    public function __construct(
        protected readonly OpenApiFactoryInterface $openApiFactory
    )
    {
    }
}
