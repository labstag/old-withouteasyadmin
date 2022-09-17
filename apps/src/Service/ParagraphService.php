<?php

namespace Labstag\Service;

use Labstag\Entity\Paragraph;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

class ParagraphService
{
    public function __construct(protected $paragraphsclass, protected Environment $environment)
    {
    }

    public function getAll($entity)
    {
        $data = [];
        foreach ($this->paragraphsclass as $row) {
            $inUse = $row->useIn();
            $type  = $row->getType();
            $name  = $row->getName();
            if (in_array($entity::class, $inUse)) {
                $data[$name] = $type;
            }
        }

        return $data;
    }

    public function getEntity(Paragraph $paragraph)
    {
        $field      = $this->getEntityField($paragraph);
        $reflection = $this->setReflection($paragraph);
        $entity     = null;
        $propertyAccessor   = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() == $field) {
                $entities = $propertyAccessor->getValue($paragraph, $field);
                $entity   = (0 != (is_countable($entities) ? count($entities) : 0)) ? $entities[0] : null;

                break;
            }
        }

        return $entity;
    }

    public function getEntityField(Paragraph $paragraph)
    {
        $childentity = $this->getTypeEntity($paragraph);
        $field       = null;
        $reflection  = $this->setReflection($childentity);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if ('paragraph' == $reflectionProperty->getName()) {
                preg_match('#inversedBy=\"(.*)\"#m', (string) $reflectionProperty->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Paragraph $paragraph)
    {
        $type = $paragraph->getType();
        $name = '';
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $name = $row->getName();

                break;
            }
        }

        return $name;
    }

    public function getNameByCode($code)
    {
        $name = '';
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $code) {
                $name = $row->getName();

                break;
            }
        }

        return $name;
    }

    public function getTypeEntity(Paragraph $paragraph)
    {
        $type   = $paragraph->getType();
        $paragraph = null;
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $paragraph = $row->getEntity();

                break;
            }
        }

        return $paragraph;
    }

    public function getTypeForm(Paragraph $paragraph)
    {
        $type = $paragraph->getType();
        $form = null;
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Paragraph $paragraph)
    {
        $type = $paragraph->getType();
        $show = false;
        foreach ($this->paragraphsclass as $row) {
            if ($row->getType() == $type) {
                $show = $row->isShowForm();

                break;
            }
        }

        return $show;
    }

    public function showContent(Paragraph $paragraph)
    {
        $type   = $paragraph->getType();
        $entity = $this->getEntity($paragraph);
        $html   = new Response();
        if (!is_null($entity)) {
            foreach ($this->paragraphsclass as $row) {
                if ($type == $row->getType()) {
                    $html = $row->show($entity);

                    break;
                }
            }
        }

        return $html;
    }

    private function setReflection($entity): ReflectionClass
    {
        return new ReflectionClass($entity);
    }
}
