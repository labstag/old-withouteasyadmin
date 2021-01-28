<?php

namespace Labstag\Swagger;

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

    protected NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $docs          = $this->decorated->normalize($object, $format, $context);
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
                                        'example' => 'username',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $docs['paths']['/api/search/user']['get'] = $statsEndpoint;

        return $docs;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
