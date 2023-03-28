<?php

declare(strict_types=1);

namespace Labstag\OpenApi;

use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use Labstag\Lib\OpenApiLib;
use Symfony\Component\HttpFoundation\Response;

class AttachmentOpenApi extends OpenApiLib
{
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->openApiFactory->__invoke($context);
        $paths   = $openApi->getPaths();
        $paths->addPath(
            '/api/attachment/delete/{entity}',
            new PathItem(
                description: 'Post Img',
                delete: new Operation(
                    parameters: $this->setParameters(),
                    responses: $this->setResponses()
                )
            )
        );

        return $openApi;
    }

    public function setParameters(): array
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
                'name'        => '_token',
                'in'          => 'query',
                'required'    => true,
                'description' => 'token',
                'schema'      => ['type' => 'string'],
            ],
        ];
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
}
