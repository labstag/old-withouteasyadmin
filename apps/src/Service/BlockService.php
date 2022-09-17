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

    /**
     * @return array<int|string, mixed>
     */
    public function getAll(): array
    {
        $data = [];
        foreach ($this->blocksclass as $row) {
            $type        = $row->getType();
            $name        = $row->getName();
            $data[$name] = $type;
        }

        return $data;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getCustomBlock(): array
    {
        $blocks = $this->blockRepository->findBy(
            ['type' => 'custom']
        );

        $data = [];
        foreach ($blocks as $block) {
            $title        = $block->getTitle();
            $id           = $block->getId();
            $data[$title] = $id;
        }

        return $data;
    }

    public function getEntity(Block $block)
    {
        $field            = $this->getEntityField($block);
        $reflection       = $this->setReflection($block);
        $entity           = null;
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() == $field) {
                $entities = $propertyAccessor->getValue($block, $field);
                $entity   = (0 != (is_countable($entities) ? count($entities) : 0)) ? $entities[0] : null;

                break;
            }
        }

        return $entity;
    }

    public function getEntityField(Block $block)
    {
        $childentity = $this->getTypeEntity($block);
        $field       = null;
        $reflection  = $this->setReflection($childentity);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if ('block' == $reflectionProperty->getName()) {
                preg_match('#inversedBy=\"(.*)\"#m', (string) $reflectionProperty->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Block $block)
    {
        $type = $block->getType();
        $form = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getName();

                break;
            }
        }

        return $form;
    }

    /**
     * @return array<string, string>
     */
    public function getRegions(): array
    {
        return [
            'header'  => 'header',
            'content' => 'content',
            'footer'  => 'footer',
        ];
    }

    public function getTypeEntity(Block $block)
    {
        $type  = $block->getType();
        $block = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $block = $row->getEntity();

                break;
            }
        }

        return $block;
    }

    public function getTypeForm(Block $block)
    {
        $type = $block->getType();
        $form = null;
        foreach ($this->blocksclass as $row) {
            if ($row->getType() == $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Block $block)
    {
        $type = $block->getType();
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
