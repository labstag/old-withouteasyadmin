<?php

namespace Labstag\Swagger;

use ArrayObject;
use Labstag\Controller\Api\AttachmentDecorator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Adds the Swagger documentation for the AttachmentDecorator.
 *
 * @see AttachmentDecorator
 */
final class AttachmentSwaggerDecorator implements NormalizerInterface
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
        $this->setDeleteAttachment($docs);

        return $docs;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->normalizer->supportsNormalization($data, $format);
    }

    private function setDeleteAttachment(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'Post Img.',
            'tags'       => ['Attachment'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/attachment/delete/{entity}']['delete'] = $statsEndpoint;
    }

    /**
     * @return array<int, mixed[]>
     */
    private function setParameters(): array
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

    private function setResponses(): array
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
