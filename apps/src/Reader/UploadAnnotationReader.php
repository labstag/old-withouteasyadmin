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

    public function enableAttachment(
        mixed $annotations,
        array $fields
    ): bool
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
    public function getUploadableFields(mixed $entity): array
    {
        $properties = [];
        if (!$this->isUploadable($entity)) {
            return $properties;
        }

        $reflection = new ReflectionClass($entity::class);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            $attributes = $reflectionProperty->getAttributes();
            foreach ($attributes as $attribute) {
                if ($attribute->getName() == UploadableField::class) {
                    $properties[$reflectionProperty->getName()] = $attribute->newInstance();
                }
            }
        }

        return $properties;
    }

    private function isUploadable(mixed $entity): bool
    {
        $reflection = new ReflectionClass($entity::class);

        $uploadable = false;
        foreach ($reflection->getAttributes() as $attribute) {
            if ($attribute->getName() === Uploadable::class) {
                $uploadable = true;

                break;
            }
        }

        return $uploadable;
    }
}
