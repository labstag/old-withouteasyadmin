<?php

namespace Labstag\Service;

use Labstag\Entity\Block;
use Labstag\Repository\BlockRepository;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

class BlockService
{
    public function __construct(
        protected $blocksclass,
        protected BlockRepository $blockRepository
    )
    {
    }

    public function getAll()
    {
        $data = [];
        foreach ($this->blocksclass as $row) {
            $type        = $row->getType();
            $name        = $row->getName();
            $data[$name] = $type;
        }

        return $data;
    }

    public function getEntity(Block $block)
    {
        $field      = $this->getEntityField($block);
        $reflection = $this->setReflection($block);
        $entity     = null;
        $accessor   = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $property) {
            if ($property->getName() == $field) {
                $entities = $accessor->getValue($block, $field);
                $entity   = (0 != (is_countable($entities) ? count($entities) : 0)) ? $entities[0] : null;

                break;
            }
        }

        return $entity;
    }

    public function getEntityField(Block $entity)
    {
        $childentity = $this->getTypeEntity($entity);
        $field       = null;
        $reflection  = $this->setReflection($childentity);
        foreach ($reflection->getProperties() as $property) {
            if ('block' == $property->getName()) {
                preg_match('/inversedBy=\"(.*)\"/m', (string) $property->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Block $entity)
    {
        $type = $entity->getType();
        $form = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getName();

                break;
            }
        }

        return $form;
    }

    public function getRegions()
    {
        return [
            'header'  => 'header',
            'content' => 'content',
            'footer'  => 'footer',
        ];
    }

    public function getTypeEntity(Block $entity)
    {
        $type   = $entity->getType();
        $entity = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $entity = $row->getEntity();

                break;
            }
        }

        return $entity;
    }

    public function getTypeForm(Block $entity)
    {
        $type = $entity->getType();
        $form = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Block $entity)
    {
        $type = $entity->getType();
        $show = false;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $show = $row->isShowForm();

                break;
            }
        }

        return $show;
    }

    public function showContent(Block $block, $content)
    {
        $type   = $block->getType();
        $entity = $this->getEntity($block);
        $html   = new Response();
        if (!is_null($entity)) {
            foreach ($this->blocksclass as $row) {
                if ($type == $row->getType()) {
                    $html = $row->show($entity, $content);

                    break;
                }
            }
        }

        return $html;
    }

    protected function setReflection($entity): ReflectionClass
    {
        return new ReflectionClass($entity);
    }
}
