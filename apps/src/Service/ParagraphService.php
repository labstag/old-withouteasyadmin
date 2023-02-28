<?php

namespace Labstag\Service;

use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Entity\Post;
use Labstag\Interfaces\FrontInterface;
use Labstag\RequestHandler\ParagraphRequestHandler;
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
        protected ParagraphRequestHandler $paragraphRequestHandler
    )
    {
    }

    public function add(
        mixed $entity,
        string $code
    ): void
    {
        $method = $this->getMethod($entity);
        if (is_null($method)) {
            return;
        }

        $position = (is_countable($entity->getParagraphs()) ? count($entity->getParagraphs()) : 0) + 1;

        $paragraph = new Paragraph();
        $old = clone $paragraph;
        $paragraph->setType($code);
        $paragraph->setPosition($position);
        call_user_func([$paragraph, $method], $entity);
        $this->paragraphRequestHandler->handle($old, $paragraph);
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getAll(mixed $entity): array
    {
        $data = [];
        foreach ($this->rewindableGenerator as $row) {
            $inUse = $row->useIn();
            $type = $row->getType();
            $name = $row->getName();
            if (in_array($entity::class, $inUse)) {
                $data[$name] = $type;
            }
        }

        return $data;
    }

    public function getEntity(Paragraph $paragraph): mixed
    {
        $entity = null;
        $field = $this->getEntityField($paragraph);
        if (is_null($field)) {
            return $entity;
        }

        $reflectionClass = new ReflectionClass($paragraph);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() === $field) {
                $entities = $propertyAccessor->getValue($paragraph, $field);
                $entity = (0 != (is_countable($entities) ? count($entities) : 0)) ? $entities[0] : null;

                break;
            }
        }

        return $entity;
    }

    public function getEntityField(Paragraph $paragraph): ?string
    {
        $field = null;
        $childentity = $this->getTypeEntity($paragraph);
        if (is_null($childentity)) {
            return $field;
        }

        $reflectionClass = new ReflectionClass($childentity);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ('paragraph' == $reflectionProperty->getName()) {
                preg_match('#inversedBy=\"(.*)\", #m', (string) $reflectionProperty->getDocComment(), $matches);
                $field = $matches[1] ?? $field;

                break;
            }
        }

        return $field;
    }

    public function getName(Paragraph $paragraph): string
    {
        $type = $paragraph->getType();
        $name = '';
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $type) {
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
            if ($row->getType() == $code) {
                $name = $row->getName();

                break;
            }
        }

        return $name;
    }

    public function getTypeEntity(Paragraph $paragraph): ?string
    {
        $type = $paragraph->getType();
        $paragraph = null;
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $type) {
                $paragraph = $row->getEntity();

                break;
            }
        }

        return $paragraph;
    }

    public function getTypeForm(Paragraph $paragraph): ?string
    {
        $type = $paragraph->getType();
        $form = null;
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $type) {
                $form = $row->getForm();

                break;
            }
        }

        return $form;
    }

    public function isShow(Paragraph $paragraph): bool
    {
        $type = $paragraph->getType();
        $show = false;
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $type) {
                $show = $row->isShowForm();

                break;
            }
        }

        return $show;
    }

    public function setData(Paragraph $paragraph): void
    {
        $entity = $this->getEntity($paragraph);
        if (is_null($entity)) {
            return;
        }

        $type = $paragraph->getType();
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $type) {
                $row->setData($paragraph);

                break;
            }
        }
    }

    public function showContent(Paragraph $paragraph): ?Response
    {
        $type = $paragraph->getType();
        $entity = $this->getEntity($paragraph);
        $html = null;
        if (is_null($entity)) {
            return $html;
        }

        foreach ($this->rewindableGenerator as $row) {
            if ($type == $row->getType()) {
                $html = $row->show($entity);

                break;
            }
        }

        return $html;
    }

    public function showTemplate(Paragraph $paragraph): ?array
    {
        $type = $paragraph->getType();
        $entity = $this->getEntity($paragraph);
        $template = null;
        if (is_null($entity)) {
            return $template;
        }

        foreach ($this->rewindableGenerator as $row) {
            if ($type == $row->getType()) {
                $template = $row->template($entity);

                break;
            }
        }

        return $template;
    }

    private function getMethod(FrontInterface $front): ?string
    {
        return match (true) {
            $front instanceof Chapter => 'setChapter',
            $front instanceof History => 'setHistory',
            $front instanceof Layout => 'setLayout',
            $front instanceof Memo => 'setMemo',
            $front instanceof Page => 'setPage',
            $front instanceof Post => 'setPost',
            default => null,
        };
    }
}
