<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

class ActionsOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);

        $functions = [
            'setEmpty',
            'setEmpties',
            'setEmptyAll',
            'setRestore',
            'setRestories',
            'setDestroy',
            'setDestroies',
            'setDelete',
            'setDeleties',
            'setWorkflow',
        ];
        foreach ($functions as $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $openApi = call_user_func($callable, $openApi);
        }

        return $openApi;
    }

    protected function setDelete(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/delete/{entity}/{id}',
            new PathItem(
                description: 'delete',
                delete: new Operation(
                    summary: 'delete',
                    tags: ['Actions'],
                    parameters: $this->setParametersDeleteDestroyRestore(),
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }

    protected function setDeleties(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/deleties/{entity}',
            new PathItem(
                description: 'delete',
                delete: new Operation(
                    summary: 'delete',
                    tags: ['Actions'],
                    parameters: $this->setParametersDeletiesEmpties(),
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }

    protected function setDestroies(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/destroies/{entity}',
            new PathItem(
                description: 'destroies',
                delete: new Operation(
                    summary: 'destroies',
                    tags: ['Actions'],
                    parameters: $this->setParametersRestoreDestroy(),
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }

    protected function setDestroy(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/destroy/{entity}/{id}',
            new PathItem(
                description: 'destroy',
                delete: new Operation(
                    summary: 'destroy',
                    tags: ['Actions'],
                    parameters: $this->setParametersDeleteDestroyRestore(),
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }

    protected function setEmpties(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/empties',
            new PathItem(
                description: 'empties',
                delete: new Operation(
                    summary: 'empties',
                    tags: ['Actions'],
                    parameters: $this->setParametersDeletiesEmpties(),
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }

    protected function setEmpty(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/empty/{entity}',
            new PathItem(
                description: 'empty entity',
                delete: new Operation(
                    summary: 'empty entity',
                    tags: ['Actions'],
                    parameters: [
                        [
                            'name'        => 'entity',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'entity',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => '_token',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'token',
                            'schema'      => ['type' => 'string'],
                        ],
                    ],
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }

    protected function setEmptyAll(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/emptyall',
            new PathItem(
                description: 'empty all',
                delete: new Operation(
                    tags: ['Actions'],
                    summary: 'empty all',
                    parameters: [
                        [
                            'name'        => '_token',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'token',
                            'schema'      => ['type' => 'string'],
                        ],
                    ],
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }

    protected function setParametersDeleteDestroyRestore(): array
    {
        return [
            [
                'name'        => 'entity',
                'in'          => 'query',
                'required'    => true,
                'description' => 'entity',
                'schema'      => ['type' => 'string'],
            ],
            [
                'name'        => 'id',
                'in'          => 'query',
                'required'    => true,
                'description' => 'id',
                'schema'      => ['type' => 'string'],
            ],
            [
                'name'        => '_token',
                'in'          => 'query',
                'required'    => true,
                'description' => 'token',
                'schema'      => ['type' => 'string'],
            ],
        ];
    }

    protected function setParametersDeletiesEmpties(): array
    {
        return [
            [
                'name'        => 'entities',
                'in'          => 'query',
                'required'    => true,
                'description' => 'entities',
                'schema'      => ['type' => 'string'],
            ],
            [
                'name'        => '_token',
                'in'          => 'query',
                'required'    => true,
                'description' => 'token',
                'schema'      => ['type' => 'string'],
            ],
        ];
    }

    protected function setParametersRestoreDestroy(): array
    {
        return [
            [
                'name'        => 'entity',
                'in'          => 'query',
                'required'    => true,
                'description' => 'entity',
                'schema'      => ['type' => 'string'],
            ],
            [
                'name'        => 'entities',
                'in'          => 'query',
                'required'    => true,
                'description' => 'entities',
                'schema'      => ['type' => 'string'],
            ],
            [
                'name'        => '_token',
                'in'          => 'query',
                'required'    => true,
                'description' => 'token',
                'schema'      => ['type' => 'string'],
            ],
        ];
    }

    protected function setResponses(): array
    {
        return [
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
        ];
    }

    protected function setRestore(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/restore/{entity}/{id}',
            new PathItem(
                description: 'restore',
                post: new Operation(
                    summary: 'restore.',
                    tags: ['Actions'],
                    parameters: $this->setParametersDeleteDestroyRestore(),
                    responses: $this->setResponses(),
                ),
            )
        );

        return $openApi;
    }

    protected function setRestories(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/restories/{entity}',
            new PathItem(
                description: 'restories',
                post: new Operation(
                    summary: 'restories.',
                    tags: ['Actions'],
                    parameters: $this->setParametersRestoreDestroy(),
                    responses: $this->setResponses(),
                ),
            )
        );

        return $openApi;
    }

    protected function setWorkflow(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/actions/workflow/{entity}/{state}/{id}',
            new PathItem(
                description: 'workflow',
                post: new Operation(
                    summary: 'workflow.',
                    tags: ['Actions'],
                    parameters: [
                        [
                            'name'        => 'entity',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'entity',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'state',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'state',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => 'id',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'id',
                            'schema'      => ['type' => 'string'],
                        ],
                        [
                            'name'        => '_token',
                            'in'          => 'query',
                            'required'    => true,
                            'description' => 'token',
                            'schema'      => ['type' => 'string'],
                        ],
                    ],
                    responses: $this->setResponses(),
                )
            )
        );

        return $openApi;
    }
}
