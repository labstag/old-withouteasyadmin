<?php

namespace Labstag\Swagger;

use ArrayObject;
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
    public function __construct(private NormalizerInterface $decorated)
    {
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
        $this->setEmpty($docs);
        $this->setEmpties($docs);
        $this->setEmptyAll($docs);
        $this->setRestore($docs);
        $this->setRestories($docs);
        $this->setDestroy($docs);
        $this->setDestroies($docs);
        $this->setDelete($docs);
        $this->setDeleties($docs);
        $this->setWorkflow($docs);

        return $docs;
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    private function setDelete(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Delete.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersDeleteDestroyRestore(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/delete/{entity}/{id}']['delete'] = $statsEndpoint;
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

    private function setDeleties(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'Delete.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersDeletiesEmpties(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/deleties/{entity}']['delete'] = $statsEndpoint;
    }

    private function setDestroies(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'destroy.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersRestoreDestroy(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/destroies/{entity}']['delete'] = $statsEndpoint;
    }

    private function setDestroy(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'destroy.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersDeleteDestroyRestore(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/destroy/{entity}/{id}']['delete'] = $statsEndpoint;
    }

    private function setEmpties(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'empty entity.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersDeletiesEmpties(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/empties']['delete'] = $statsEndpoint;
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
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/empty/{entity}']['delete'] = $statsEndpoint;
    }

    private function setEmptyAll(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'empty entity.',
            'tags'       => ['Actions'],
            'parameters' => [
                [
                    'name'        => '_token',
                    'in'          => 'query',
                    'required'    => true,
                    'description' => 'token',
                    'schema'      => ['type' => 'string'],
                ],
            ],
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/emptyall']['delete'] = $statsEndpoint;
    }

    private function setRestore(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'restore.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersDeleteDestroyRestore(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/restore/{entity}/{id}']['post'] = $statsEndpoint;
    }

    private function setParametersRestoreDestroy()
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

    private function setParametersDeletiesEmpties()
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

    private function setParametersDeleteDestroyRestore()
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


    private function setRestories(&$docs)
    {
        $statsEndpoint = [
            'summary'    => 'restore.',
            'tags'       => ['Actions'],
            'parameters' => $this->setParametersRestoreDestroy(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/restories/{entity}']['post'] = $statsEndpoint;
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
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/actions/workflow/{entity}/{state}/{id}']['post'] = $statsEndpoint;
    }
}
