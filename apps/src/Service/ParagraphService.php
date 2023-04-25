<?php

namespace Labstag\Service;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Entity\Post;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Repository\ParagraphRepository;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

class ParagraphService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator,
        protected Environment $twigEnvironment,
        protected ParagraphRepository $paragraphRepository
    ) {
    }

    public function add(
        EntityFrontInterface $entityFront,
        string $code
    ): void {
        $method = $this->getMethod($entityFront);
        if (is_null($method)) {
            return;
        }

        $position = (is_countable($entityFront->getParagraphs()) ? count($entityFront->getParagraphs()) : 0) + 1;

        $paragraph = new Paragraph();
        $paragraph->setType($code);
        $paragraph->setPosition($position);
        /** @var callable $callable */
        $callable = [
            $paragraph,
            $method,
        ];
        call_user_func($callable, $entityFront);
        $this->paragraphRepository->save($paragraph);
    }

    public function getAll(EntityFrontInterface $entityFront): array
    {
        $data = [];
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            $inUse = $row->useIn();
            $type  = $row->getType();
            $name  = $row->getName();
            if (in_array($entityFront::class, $inUse)) {
                $data[$name] = $type;
            }
        }

        return $data;
    }

    public function getEntity(Paragraph $paragraph): ?EntityParagraphInterface
    {
        $entity = null;
        $field  = $this->getEntityField($paragraph);
        if (is_null($field)) {
            return $entity;
        }

        $reflectionClass  = new ReflectionClass($paragraph);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() === $field) {
                $entities = $propertyAccessor->getValue($paragraph, $field);
                if (!$entities instanceof PersistentCollection || !$entities->offsetExists(0)) {
                    continue;
                }

                $entity = $entities->offsetGet(0);

                break;
            }
        }

        if (!$entity instanceof EntityParagraphInterface) {
            $entity = null;
        }

        return $entity;
    }

    public function getEntityField(Paragraph $paragraph): ?string
    {
        $field       = null;
        $childentity = $this->getTypeEntity($paragraph);
        if (!is_string($childentity)) {
            return $field;
        }

        $childentity = new $childentity();

        $reflectionClass = new ReflectionClass($childentity);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ('paragraph' == $reflectionProperty->getName()) {
                $attributes = $reflectionProperty->getAttributes();
                $attribute  = $attributes[0];
                $arguments  = $attribute->getArguments();
                $field      = $arguments['inversedBy'] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Paragraph $paragraph): string
    {
        $type = $paragraph->getType();
        if (is_null($type)) {
            return '';
        }

        $name = '';
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $type) {
                $name = $row->getName();

                break;
            }
        }

        return $name;
    }

    public function getNameByCode(string $code): string
    {
        $name = '';
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $code) {
                $name = $row->getName();

                break;
            }
        }

        return $name;
    }

    public function getTypeEntity(Paragraph $paragraph): ?string
    {
        $type = $paragraph->getType();
        if (is_null($type)) {
            return null;
        }

        $paragraph = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $type) {
                $paragraph = $row->getEntity();

                break;
            }
        }

        return $paragraph;
    }

    public function getTypeForm(Paragraph $paragraph): ?string
    {
        $type = $paragraph->getType();
        if (is_null($type)) {
            return null;
        }

        $form = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Paragraph $paragraph): bool
    {
        $type = $paragraph->getType();
        if (is_null($type)) {
            return false;
        }

        $show = false;
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $type) {
                $show = $row->isShowForm();

                break;
            }
        }

        return $show;
    }

    public function setData(Paragraph $paragraph): void
    {
        $entity = $this->getEntity($paragraph);
        $type   = $paragraph->getType();
        if ($entity instanceof EntityParagraphInterface || is_null($type)) {
            return;
        }

        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($row->getType() === $type) {
                $row->setData($paragraph);

                break;
            }
        }
    }

    public function showContent(Paragraph $paragraph): ?Response
    {
        $type   = $paragraph->getType();
        $entity = $this->getEntity($paragraph);
        if (!$entity instanceof EntityParagraphInterface || is_null($type)) {
            return null;
        }

        $html = null;
        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($type === $row->getType()) {
                $html = $row->show($entity);

                break;
            }
        }

        return $html;
    }

    public function showTemplate(Paragraph $paragraph): ?array
    {
        $type     = $paragraph->getType();
        $entity   = $this->getEntity($paragraph);
        $template = null;
        if (is_null($entity)) {
            return $template;
        }

        foreach ($this->rewindableGenerator as $row) {
            /** @var ParagraphInterface $row */
            if ($type == $row->getType()) {
                $template = $row->template($entity);

                break;
            }
        }

        return $template;
    }

    private function getMethod(EntityFrontInterface $entityFront): ?string
    {
        return match (true) {
            $entityFront instanceof Chapter => 'setChapter',
            $entityFront instanceof History => 'setHistory',
            $entityFront instanceof Layout  => 'setLayout',
            $entityFront instanceof Memo    => 'setMemo',
            $entityFront instanceof Page    => 'setPage',
            $entityFront instanceof Post    => 'setPost',
            default                         => null,
        };
    }
}
