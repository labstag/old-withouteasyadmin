<?php

namespace Labstag\Swagger;

use Labstag\Controller\Api\PostalCodeController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the PostalCodeController.
 *
 * @see PostalCodeController
 */
final class PostalCodeSwaggerDecorator implements NormalizerInterface
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
        $statsEndpoint = [
            'summary' => 'Retrieves the number of books and top books (legacy endpoint).',
            'tags' => ['Postal code'],
            'parameters' => [
                [
                    "name" => "postalcode",
                    "in" => "query",
                    "required" => false,
                    "description" => "postal code",
                    "schema" => [
                        "type" => "string"
                    ]
                ],
                [
                    "name" => "postalcode_startsWith",
                    "in" => "query",
                    "required" => false,
                    "description" => "the first characters or letters of a postal code",
                    "schema" => [
                        "type" => "string"
                    ]
                ],
                [
                    "name" => "placename",
                    "in" => "query",
                    "required" => false,
                    "description" => "all fields : placename,postal code, country, admin name",
                    "schema" => [
                        "type" => "string"
                    ]
                ],
                [
                    "name" => "placename_startsWith",
                    "in" => "query",
                    "required" => false,
                    "description" => "the first characters of a place name",
                    "schema" => [
                        "type" => "string"
                    ]
                ],
                [
                    "name" => "country",
                    "in" => "query",
                    "required" => false,
                    "description" => "Default is all countries. The country parameter may occur more than once, example: country=FR&country=GP",
                    "schema" => [
                        "type" => "string"
                    ]
                ],
                [
                    "name" => "operator",
                    "in" => "query",
                    "required" => false,
                    "description" => "the operator 'AND' searches for all terms in the placename parameter, the operator 'OR' searches for any term, default = AND",
                    "schema" => [
                        "type" => "string"
                    ]
                ]
            ],
            'responses' => [
                Response::HTTP_OK => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'books_count' => [
                                        'type' => 'integer',
                                        'example' => 997,
                                    ],
                                    'topbooks_count' => [
                                        'type' => 'integer',
                                        'example' => 101,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $docs['paths']['/api/codepostal']['get'] = $statsEndpoint;

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
