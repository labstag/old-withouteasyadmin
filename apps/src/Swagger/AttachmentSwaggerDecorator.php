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
        $this->setProfilAvatar($docs);
        $this->setUserAvatar($docs);
        $this->setPostImg($docs);
        $this->setMemoFond($docs);
        $this->setEditoFond($docs);

        return $docs;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $this->normalizer->supportsNormalization($data, $format);
    }

    private function setEditoFond(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'edito fond.',
            'tags'       => ['Attachment'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/attachment/edito/fond/{entity}']['delete'] = $statsEndpoint;
    }

    private function setMemoFond(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'node interne Fond.',
            'tags'       => ['Attachment'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/attachment/memo/fond/{entity}']['delete'] = $statsEndpoint;
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

    private function setPostImg(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'Post Img.',
            'tags'       => ['Attachment'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/attachment/post/img/{entity}']['delete'] = $statsEndpoint;
    }

    private function setProfilAvatar(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'Profil avatar.',
            'tags'       => ['Attachment'],
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

        $docs['paths']['/api/attachment/profil/avatar']['delete'] = $statsEndpoint;
    }

    /**
     * @return array<int, array{content: array{application/json: array{schema: array{type: string, properties: array{isvalid: array{type: string, example: bool}}}}}}>
     */
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

    private function setUserAvatar(&$docs): void
    {
        $statsEndpoint = [
            'summary'    => 'User avatar.',
            'tags'       => ['Attachment'],
            'parameters' => $this->setParameters(),
            'responses'  => $this->setResponses(),
        ];

        $docs['paths']['/api/attachment/user/avatar/{entity}']['delete'] = $statsEndpoint;
    }
}
