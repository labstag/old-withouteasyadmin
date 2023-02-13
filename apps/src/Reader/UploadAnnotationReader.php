<?php

namespace Labstag\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UploadAnnotationReader
{
    public function __construct(protected AnnotationReader $annotationReader)
    {
    }

    public function enableAttachment($annotations, $fields)
    {
        $enable = false;
        foreach (array_keys($annotations) as $key) {
            if (array_key_exists($key, $fields)) {
                $enable = true;

                break;
            }

            foreach ($fields as $field) {
                $type = $field->getConfig()->getType()->getInnerType();
                if ($type instanceof CollectionType) {
                    foreach ($field->all() as $data) {
                        $enable = $this->enableAttachment($annotations, $data->all());
                        if ($enable) {
                            break;
                        }
                    }

                    if ($enable) {
                        break;
                    }
                }
            }
        }

        return $enable;
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

    protected function setReflection($entity): ReflectionClass
    {
        return new ReflectionClass($entity::class);
    }

    private function isUploadable($entity): bool
    {
        $reflection = $this->setReflection($entity);

        $uploadable = $this->annotationReader->getClassAnnotation($reflection, Uploadable::class);

        return !is_null($uploadable);
    }
}
