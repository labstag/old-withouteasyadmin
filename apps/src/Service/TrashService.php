<?php

namespace Labstag\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Labstag\Annotation\Trashable;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class TrashService
{
    public function all()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Repository');
        $data = [];
        foreach ($finder as $file) {
            $repository  = 'Labstag\\Repository\\'.$file->getFilenameWithoutExtension();
            $isTrashable = $this->isTrashable($repository);
            if ($isTrashable) {
                $data[] = [
                    'properties' => $this->getProperties($repository),
                    'entity'     => str_replace(
                        'Repository',
                        '',
                        'Labstag\\Entity\\'.$file->getFilenameWithoutExtension()
                    ),
                ];
            }
        }

        return $data;
    }

    public function isTrashable(string $repository): bool
    {
        $reader     = new AnnotationReader();
        $reflection = $this->setReflection($repository);
        $annotation = $reader->getClassAnnotation($reflection, Trashable::class);

        return !is_null($annotation);
    }

    public function getProperties(string $repository)
    {
        $reader     = new AnnotationReader();
        $properties = [];
        if (!$this->isTrashable($repository)) {
            return $properties;
        }

        $reflection = $this->setReflection($repository);
        $properties = $reader->getClassAnnotations($reflection);
        $properties = $properties[0];

        return $properties;
    }

    protected function setReflection(string $repository): ReflectionClass
    {
        return new ReflectionClass($repository);
    }
}
