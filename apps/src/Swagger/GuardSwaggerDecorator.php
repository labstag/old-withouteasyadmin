<?php

namespace Labstag\Swagger;

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

    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);
        $this->setUser($docs);
        $this->setGroup($docs);

        return $docs;
    }

    private function setUser(&$docs)
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
                    'name'        => 'token',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'token',
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
                                    'state' => [
                                        'type'    => 'boolean',
                                        'example' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $docs['paths']['/api/guard/user/{route}/{user}']['post'] = $statsEndpoint;
    }

    private function setGroup(&$docs)
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
                    'name'        => 'token',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'token',
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
                                    'state' => [
                                        'type'    => 'boolean',
                                        'example' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $docs['paths']['/api/guard/group/{route}/{groupe}']['post'] = $statsEndpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
