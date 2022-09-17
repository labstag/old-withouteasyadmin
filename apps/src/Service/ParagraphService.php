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
        $accessor   = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $property) {
            if ($property->getName() == $field) {
                $entities = $accessor->getValue($paragraph, $field);
                $entity   = (0 != (is_countable($entities) ? count($entities) : 0)) ? $entities[0] : null;

                break;
            }
        }

        return $entity;
    }

    public function getEntityField(Paragraph $entity)
    {
        $childentity = $this->getTypeEntity($entity);
        $field       = null;
        $reflection  = $this->setReflection($childentity);
        foreach ($reflection->getProperties() as $property) {
            if ('paragraph' == $property->getName()) {
                preg_match('#inversedBy=\"(.*)\"#m', (string) $property->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Paragraph $entity)
    {
        $type = $entity->getType();
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

    public function isShow(Paragraph $entity)
    {
        $type = $entity->getType();
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
