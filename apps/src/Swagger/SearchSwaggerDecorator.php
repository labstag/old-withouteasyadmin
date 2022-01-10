<?php

namespace Labstag\Swagger;

use ArrayObject;
use Labstag\Controller\Api\SearchController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the SearchController.
 *
 * @see SearchController
 */
final class SearchSwaggerDecorator implements NormalizerInterface
{

    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @inheritdoc
     */
    public function normalize(
        $object,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|null
    {
        $docs = $this->decorated->normalize($object, $format, $context);
        $this->setUsers($docs);
        $this->setLibelles($docs);
        $this->setGroupes($docs);
        $this->setCategories($docs);

        return $docs;
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    private function setCategories(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'get Categories.',
            'tags'       => ['Search'],
            'parameters' => [
                [
                    'name'        => 'name',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'name',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/search/category']['get'] = $statsEndpoint;
    }

    private function setGroupes(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'get Groupe.',
            'tags'       => ['Search'],
            'parameters' => [
                [
                    'name'        => 'name',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'name',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/search/group']['get'] = $statsEndpoint;
    }

    private function setLibelles(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'get Libelle.',
            'tags'       => ['Search'],
            'parameters' => [
                [
                    'name'        => 'name',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'name',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/search/libelle']['get'] = $statsEndpoint;
    }

    private function setUsers(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'get Users.',
            'tags'       => ['Search'],
            'parameters' => [
                [
                    'name'        => 'name',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'name',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => [
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
            ],
        ];

        $docs['paths']['/api/search/user']['get'] = $statsEndpoint;
    }
}
