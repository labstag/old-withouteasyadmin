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

    public function getEntity(Block $block)
    {
        $field    = $this->getEntityField($block);
        $method   = 'get'.ucfirst((string) $field).'s';
        $entities = $block->{$method}();

        return $entities[0];
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

    public function showContent(string $code)
    {
        $block = $this->blockRepository->findOneBy(
            ['code' => $code]
        );
        if (!$block instanceof Block) {
            return new Response();
        }

        $type       = $block->getType();
        $field      = $this->getEntityField($block);
        $reflection = $this->setReflection($block);
        $entity     = null;
        $accessor   = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $property) {
            if ($property->getName() == $field) {
                $entities = $accessor->getValue($block, $field);
                $entity   = $entities[0];

                break;
            }
        }

        $html = new Response();
        if (!is_null($entity)) {
            foreach ($this->blocksclass as $row) {
                if ($type == $row->getType()) {
                    $html = $row->show($entity);

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
