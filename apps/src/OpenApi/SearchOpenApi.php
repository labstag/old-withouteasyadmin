<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

class SearchOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $functions = [
            'setUsers',
            'setLibelles',
            'setGroupes',
            'setCategories',
        ];

        foreach ($functions as $function) {
            $openApi = call_user_func([$this, $function], $openApi);
        }

        return $openApi;
    }

    public function setCategories(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/search/category',
            new PathItem(
                description: 'Category',
                get: new Operation(
                    tags: ['Search'],
                    summary: 'Category',
                    responses: $this->getResponses(),
                    parameters: $this->setParameters(),
                ),
            )
        );

        return $openApi;
    }

    public function setGroupes(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/search/group',
            new PathItem(
                description: 'Groupe',
                get: new Operation(
                    tags: ['Search'],
                    summary: 'Groupe',
                    responses: $this->getResponses(),
                    parameters: $this->setParameters(),
                ),
            )
        );

        return $openApi;
    }

    public function setLibelles(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->addPath(
            '/api/search/libelle',
            new PathItem(
                description: 'Libelle',
                get: new Operation(
                    tags: ['Search'],
                    summary: 'Libelle',
                    responses: $this->getResponses(),
                    parameters: $this->setParameters(),
                ),
            ),
        );

        return $openApi;
    }

    public function setParameters(): array
    {
        return [
            [
                'name'        => 'name',
                'in'          => 'query',
                'required'    => true,
                'description' => 'name',
                'schema'      => ['type' => 'string'],
            ],
        ];
    }

    public function setUsers(OpenApi $openApi): OpenApi
    {
        $paths = $openApi->getPaths();
        $paths->getPath(
            '/api/search/user',
        );

        return $openApi;
    }

    private function getResponses(): array
    {
        return [
            Response::HTTP_OK => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type'       => 'object',
                            'properties' => [
                                'id'   => [
                                    'type'    => 'string',
                                    'example' => '56e96fa9-dc44-494d-885c-797c7d588449',
                                ],
                                'name' => [
                                    'type'    => 'string',
                                    'example' => 'name',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
