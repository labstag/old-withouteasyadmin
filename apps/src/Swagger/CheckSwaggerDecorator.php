<?php

namespace Labstag\Swagger;

use ArrayObject;
use Labstag\Controller\Api\CheckController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the CheckController.
 *
 * @see CheckController
 */
final class CheckSwaggerDecorator implements NormalizerInterface
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
        $docs          = $this->normalizer->normalize($object, $format, $context);
        $statsEndpoint = [
            'summary'    => 'Phone number.',
            'tags'       => ['Check'],
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

        $docs['paths']['/api/check/phone']['get'] = $statsEndpoint;

        return $docs;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->normalizer->supportsNormalization($data, $format);
    }
}
