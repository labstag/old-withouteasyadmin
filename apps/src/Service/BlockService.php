<?php

namespace Labstag\Service;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Block;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Repository\BlockRepository;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

class BlockService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator,
        protected BlockRepository $blockRepository
    ) {
    }

    public function getAll(): array
    {
        $data = [];
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            $type        = $row->getType();
            $name        = $row->getName();
            $data[$name] = $type;
        }

        return $data;
    }

    public function getCustomBlock(): array
    {
        $blocks = $this->blockRepository->findBy(
            ['type' => 'custom']
        );

        $data = [];
        foreach ($blocks as $block) {
            /** @var Block $block */
            $title        = $block->getTitle();
            $id           = $block->getId();
            $data[$title] = $id;
        }

        return $data;
    }

    public function getEntity(Block $block): ?EntityBlockInterface
    {
        $entity = null;
        $field  = $this->getEntityField($block);
        if (is_null($field)) {
            return $entity;
        }

        $reflectionClass  = new ReflectionClass($block);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() === $field) {
                $entities = $propertyAccessor->getValue($block, $field);
                if (!$entities instanceof PersistentCollection || !$entities->offsetExists(0)) {
                    continue;
                }

                $entity = $entities->offsetGet(0);

                break;
            }
        }

        if (!$entity instanceof EntityBlockInterface) {
            $entity = null;
        }

        return $entity;
    }

    public function getEntityField(Block $block): ?string
    {
        $field       = null;
        $childentity = $this->getTypeEntity($block);
        if (is_null($childentity)) {
            return $field;
        }

        $childentity = new $childentity();

        $reflectionClass = new ReflectionClass($childentity);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ('block' == $reflectionProperty->getName()) {
                $attributes = $reflectionProperty->getAttributes();
                $attribute  = $attributes[0];
                $arguments  = $attribute->getArguments();
                $field      = $arguments['inversedBy'] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Block $block): ?string
    {
        $type = $block->getType();
        if (is_null($type)) {
            return null;
        }

        $form = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($row->getType() === $type) {
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

    public function getTypeEntity(Block $block): ?string
    {
        $type = $block->getType();
        if (is_null($type)) {
            return null;
        }

        $block = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($row->getType() === $type) {
                $block = $row->getEntity();

                break;
            }
        }

        return $block;
    }

    public function getTypeForm(Block $block): ?string
    {
        $type = $block->getType();
        if (is_null($type)) {
            return null;
        }

        $form = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($row->getType() === $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Block $block): bool
    {
        $type = $block->getType();
        if (is_null($type)) {
            return false;
        }

        $show = false;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($row->getType() === $type) {
                $show = $row->isShowForm();

                break;
            }
        }

        return $show;
    }

    public function showContent(
        Block $block,
        ?EntityFrontInterface $entityFront
    ): ?Response {
        $type   = $block->getType();
        $entity = $this->getEntity($block);
        if (!$entity instanceof EntityBlockInterface || is_null($type)) {
            return null;
        }

        $html = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($type === $row->getType()) {
                $html = $row->show($entity, $entityFront);

                break;
            }
        }

        return $html;
    }

    public function showTemplate(
        Block $block,
        ?EntityFrontInterface $entityFront
    ): ?array {
        $type   = $block->getType();
        $entity = $this->getEntity($block);
        if (!$entity instanceof EntityBlockInterface || is_null($type)) {
            return null;
        }

        $template = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var BlockInterface $row */
            if ($type === $row->getType()) {
                $template = $row->template($entity, $entityFront);
            }
        }

        return $template;
    }
}
