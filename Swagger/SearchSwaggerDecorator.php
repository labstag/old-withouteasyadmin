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
        $this->setUsers($docs);
        $this->setLibelles($docs);
        $this->setGroupes($docs);
        $this->setCategories($docs);

        return $docs;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->normalizer->supportsNormalization($data, $format);
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

    private function setCategories(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'get Categories.',
            'tags'       => ['Search'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->getResponses(),
        ];

        $docs['paths']['/api/search/category']['get'] = $statsEndpoint;
    }

    private function setGroupes(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'get Groupe.',
            'tags'       => ['Search'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->getResponses(),
        ];

        $docs['paths']['/api/search/group']['get'] = $statsEndpoint;
    }

    private function setLibelles(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'get Libelle.',
            'tags'       => ['Search'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->getResponses(),
        ];

        $docs['paths']['/api/search/libelle']['get'] = $statsEndpoint;
    }

    private function setParameters(): array
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

    private function setUsers(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'get Users.',
            'tags'       => ['Search'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->getResponses(),
        ];

        $docs['paths']['/api/search/user']['get'] = $statsEndpoint;
    }
}