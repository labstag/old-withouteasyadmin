<?php

namespace Labstag\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use InvalidArgumentException;

/**
 * @Annotation
 *
 * @Target("CLASS")
 */
class Trashable
{

    protected string $url;

    public function __construct(array $options)
    {
        if (empty($options['url'])) {
            throw new InvalidArgumentException("L'annotation Trashable doit avoir un attribut 'url'");
        }

        $this->url = $options['url'];
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
