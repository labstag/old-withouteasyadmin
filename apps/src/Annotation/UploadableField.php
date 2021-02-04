<?php
namespace Labstag\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use InvalidArgumentException;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableField
{

    protected $filename;

    protected $path;

    public function __construct(array $options)
    {
        if (empty($options['filename'])) {
            throw new InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'filename'");
        }

        if (empty($options['path'])) {
            throw new InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'path'");
        }

        $this->filename = $options['filename'];
        $this->path     = $options['path'];
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getPath()
    {
        return $this->path;
    }
}
