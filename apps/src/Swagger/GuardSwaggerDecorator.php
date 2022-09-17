<?php

namespace Labstag\Swagger;

use ArrayObject;
use Labstag\Controller\Api\GuardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the GuardController.
 *
 * @see GuardController
 */
final class GuardSwaggerDecorator implements NormalizerInterface
{
    public function __construct(private readonly NormalizerInterface $normalizer)
    {
    }

    /**
     * @inheritDoc
     */
    public function normalize(
        $object,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|null
    {
        $docs = $this->normalizer->normalize($object, $format, $context);
        $this->setRefUser($docs);
        $this->setRefGroup($docs);
        $this->setGroups($docs);
        $this->setGroup($docs);
        $this->setUser($docs);

        return $docs;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->normalizer->supportsNormalization($data, $format);
    }

    private function setGroup(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Group.',
            'tags'       => ['Guard'],
            'parameters' => [
                [
                    'name'        => 'groupe',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'groupe',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/guard/groups/{groupe}']['get'] = $statsEndpoint;
    }

    private function setGroups(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Groups.',
            'tags'       => ['Guard'],
            'parameters' => [],
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/guard/groups']['get'] = $statsEndpoint;
    }

    private function setRefGroup(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Group.',
            'tags'       => ['Guard'],
            'parameters' => [
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
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/guard/setgroup/{route}/{groupe}']['post'] = $statsEndpoint;
    }

    private function setRefUser(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'User.',
            'tags'       => ['Guard'],
            'parameters' => [
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
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/guard/setuser/{route}/{user}']['post'] = $statsEndpoint;
    }

    private function setResponses()
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

    private function setReturnUserGroup()
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

    private function setUser(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Group.',
            'tags'       => ['Guard'],
            'parameters' => [
                [
                    'name'        => 'user',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'user',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/guard/users/{user}']['get'] = $statsEndpoint;
    }
}
