<?php

namespace Labstag\Service;

use Labstag\Entity\Paragraph;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

class ParagraphService
{
    public function __construct(protected $paragraphsclass, protected Environment $twig)
    {
    }

    public function getEntityField(Paragraph $entity)
    {
        $childentity = $this->getTypeEntity($entity);
        $field       = null;
        $reflection  = $this->setReflection($childentity);
        foreach ($reflection->getProperties() as $property) {
            if ('paragraph' == $property->getName()) {
                preg_match('/inversedBy=\"(.*)\"/m', (string) $property->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getTypeEntity(Paragraph $entity)
    {
        $type   = $entity->getType();
        $entity = null;
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $entity = $row->getEntity();

                break;
            }
        }

        return $entity;
    }

    public function getTypeForm(Paragraph $entity)
    {
        $type = $entity->getType();
        $form = null;
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function showContent(Paragraph $paragraph)
    {
        $parent = null;
        $type   = $paragraph->getType();
        $entity = $this->getEntity($paragraph);
        $html   = new Response();
        if (!is_null($entity)) {
            foreach ($this->paragraphsclass as $row) {
                if ($type == $row->getType()) {
                    $html = $row->show($entity, $parent);

                    break;
                }
            }
        }

        return $html;
    }

    private function getEntity(Paragraph $paragraph)
    {
        $field      = $this->getEntityField($paragraph);
        $reflection = $this->setReflection($paragraph);
        $entity     = null;
        $accessor   = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $property) {
            if ($property->getName() == $field) {
                $entities = $accessor->getValue($paragraph, $field);
                $entity   = $entities[0];

                break;
            }
        }

        return $entity;
    }

    private function setReflection($entity): ReflectionClass
    {
        return new ReflectionClass($entity);
    }
}
