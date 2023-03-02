<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

class GuardOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $functions = [
            'setRefUser',
            'setRefGroup',
            'setGroups',
            'setGroup',
            'setUser',
        ];

        foreach ($functions as $function) {
            $openApi = call_user_func([$this, $function], $openApi);
        }

        return $openApi;
    }

    public function setGroup(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/guard/groups/{groupe}',
            new PathItem(
                description: 'Group',
                get: new Operation(
                    summary: 'Group.',
                    tags: ['Guard'],
                    parameters: [
                        [
                            'name'        => 'groupe',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'groupe',
                            'schema'      => ['type' => 'string'],
                        ],
                    ],
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }

    public function setGroups(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->adDPath(
            '/api/guard/groups',
            new PathItem(
                description: 'Groups',
                get: new Operation(
                    summary: 'Groups.',
                    tags: ['Guard'],
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }

    public function setRefGroup(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/guard/setgroup/{route}/{groupe}',
            new PathItem(
                description: 'Group',
                post: new Operation(
                    summary: 'Group.',
                    tags: ['Guard'],
                    parameters: [
                        [
                            'name'        => 'route',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'route',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'groupe',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'groupe',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => '_token',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'token',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'state',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'state',
                            'schema'      => ['type' => 'boolean'],
                        ],
                    ],
                    responses: [
                        Response::HTTP_OK => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type'       => 'object',
                                        'properties' => $this->setReturnUserGroup(),
                                    ],
                                ],
                            ],
                        ],
                    ]
                )
            )
        );

        return $openApi;
    }

    public function setRefUser(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/guard/setuser/{route}/{user}',
            new PathItem(
                description: 'User',
                post: new Operation(
                    summary: 'User.',
                    tags: ['Guard'],
                    parameters: [
                        [
                            'name'        => 'route',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'route',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'user',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'user',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => '_token',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'token',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'state',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'state',
                            'schema'      => ['type' => 'boolean'],
                        ],
                    ],
                    responses: [
                        Response::HTTP_OK => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type'       => 'object',
                                        'properties' => $this->setReturnUserGroup(),
                                    ],
                                ],
                            ],
                        ],
                    ]
                )
            )
        );

        return $openApi;
    }

    public function setResponses(): array
    {
        return [
            Response::HTTP_OK => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type'       => 'object',
                            'properties' => [
                                'ok' => [
                                    'type'    => 'boolean',
                                    'example' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed[]>
     */
    public function setReturnUserGroup(): array
    {
        return [
            'ok'      => [
                'type'    => 'boolean',
                'example' => true,
            ],
            'message' => [
                'type'    => 'string',
                'example' => 'Changement effectué',
            ],
        ];
    }

    public function setUser(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/guard/users/{user}',
            new PathItem(
                description: 'Group',
                get: new Operation(
                    summary: 'Group.',
                    tags: ['Guard'],
                    parameters: [
                        [
                            'name'        => 'user',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'user',
                            'schema'      => ['type' => 'string'],
                        ],
                    ],
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }
}
