<?php

namespace Labstag\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use InvalidArgumentException;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
class UploadableField
{

    protected string $filename;

    protected string $path;

    protected string $slug;

    public function __construct(array $options)
    {
        if (empty($options['filename'])) {
            throw new InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'filename'");
        }

        if (empty($options['path'])) {
            throw new InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'path'");
        }

        if (empty($options['slug'])) {
            throw new InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'slug'");
        }

        $this->filename = $options['filename'];
        $this->path = $options['path'];
        $this->slug = $options['slug'];
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSlug(): string
    {
        return strtolower((string) $this->slug);
    }
}
