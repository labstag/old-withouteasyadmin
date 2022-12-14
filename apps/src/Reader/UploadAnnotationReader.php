<?php

namespace Labstag\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use ReflectionClass;

class UploadAnnotationReader
{
    public function __construct(protected AnnotationReader $annotationReader)
    {
    }

    /**
     * Liste les champs uploadable d'une entité (sous forme de tableau associatif).
     *
     * @return mixed[]
     */
    public function getUploadableFields($entity): array
    {
        $properties = [];
        if (!$this->isUploadable($entity)) {
            return $properties;
        }

        $reflection = $this->setReflection($entity);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            $annotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, UploadableField::class);
            if (is_null($annotation)) {
                continue;
            }

            $properties[$reflectionProperty->getName()] = $annotation;
        }

        return $properties;
    }

    public function isUploadable($entity): bool
    {
        $reflection = $this->setReflection($entity);

        $uploadable = $this->annotationReader->getClassAnnotation($reflection, Uploadable::class);

        return !is_null($uploadable);
    }

    protected function setReflection($entity): ReflectionClass
    {
        return new ReflectionClass($entity::class);
    }
}
