<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

class AttachmentOpenApi implements OpenApiFactoryInterface
{

    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/attachment/phone',
            new PathItem(
                null,
                'Phone number',
                null,
                new Operation(
                    'get',
                    ['attachment'],
                    [],
                    'Phone number'
                )
            )
        );

        return $openApi;
    }
}
