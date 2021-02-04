<?php

namespace Labstag\Swagger;

use Labstag\Controller\Api\ActionsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the ActionsController.
 *
 * @see ActionsController
 */
final class ActionsSwaggerDecorator implements NormalizerInterface
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
        $this->setEmpty($docs);
        $this->setRestore($docs);
        $this->setDestroy($docs);
        $this->setDelete($docs);
        $this->setWorkflow($docs);

        return $docs;
    }

    private function setWorkflow(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'workflow.',
            'tags'       => ['Actions'],
            'parameters' => [
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
            'responses'  => [
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
        ];

        $docs['paths']['/api/actions/workflow/{entity}/{state}/{id}']['post'] = $statsEndpoint;
    }

    private function setEmpty(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'empty entity.',
            'tags'       => ['Actions'],
            'parameters' => [
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
            'responses'  => [
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
        ];

        $docs['paths']['/api/actions/empty/{entity}']['delete'] = $statsEndpoint;
    }

    private function setRestore(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'restore.',
            'tags'       => ['Actions'],
            'parameters' => [
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
            ],
            'responses'  => [
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
        ];

        $docs['paths']['/api/actions/restore/{entity}/{id}']['post'] = $statsEndpoint;
    }

    private function setDestroy(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'destroy.',
            'tags'       => ['Actions'],
            'parameters' => [
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
            ],
            'responses'  => [
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
        ];

        $docs['paths']['/api/actions/destroy/{entity}/{id}']['delete'] = $statsEndpoint;
    }

    private function setDelete(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Delete.',
            'tags'       => ['Actions'],
            'parameters' => [
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
            ],
            'responses'  => [
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
        ];

        $docs['paths']['/api/actions/delete/{entity}/{id}']['delete'] = $statsEndpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
