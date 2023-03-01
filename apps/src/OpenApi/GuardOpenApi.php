<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

class GuardOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/guard/phone',
            new PathItem(
                description: 'Phone number',
                get: new Operation(
                    operationId: 'get',
                    tags: ['Guard'],
                    summary: 'Phone number'
                )
            )
        );

        return $openApi;
    }
}
