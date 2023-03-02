<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

class CheckOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/check/phone',
            new PathItem(
                description: 'Phone number',
                get: new Operation(
                    tags: ['Check'],
                    summary: 'Phone number',
                    responses: [
                        Response::HTTP_OK => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type'       => 'object',
                                        'properties' => [
                                            'isvalid' => [
                                                'type'    => 'boolean',
                                                'example' => true,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    parameters: [
                        [
                            'name'        => 'country',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'country code',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'phone',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'phone',
                            'schema'      => ['type' => 'string'],
                        ],
                    ]
                )
            )
        );

        return $openApi;
    }
}
