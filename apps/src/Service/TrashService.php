<?php

namespace Labstag\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TrashService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator,
        protected ManagerRegistry $managerRegistry,
        protected CsrfTokenManagerInterface $csrfTokenManager
    )
    {
    }

    /**
     * @return array<int, array{name: string, properties: mixed, entity: string, total: int, token: string}>
     */
    public function all(): array
    {
        $data = [];
        foreach ($this->rewindableGenerator as $repository) {
            $isTrashable = $this->isTrashable($repository::class);
            if (!$isTrashable) {
                continue;
            }

            dump($repository::class);

            $entity = $repository->getClassName();
            $trash = $repository->findTrashForAdmin([]);
            $result = $trash->getQuery()->getResult();
            $test = is_countable($result) ? count($result) : 0;
            if (0 == $test) {
                continue;
            }

            $data[] = [
                'name'       => strtolower(substr((string) $entity, strrpos((string) $entity, '\\') + 1)),
                'properties' => $this->getProperties($repository::class),
                'entity'     => substr((string) $entity, strrpos((string) $entity, '\\') + 1),
                'total'      => $test,
                'token'      => $this->csrfTokenManager->getToken('empty')->getValue(),
            ];
        }

        return $data;
    }

    public function getProperties(string $repository)
    {
        $annotationReader = new AnnotationReader();
        $properties = [];
        if (!$this->isTrashable($repository)) {
            return $properties;
        }

        $reflection = new ReflectionClass($repository);
        $properties = $annotationReader->getClassAnnotations($reflection);

        return $properties[0];
    }

    public function isTrashable(string $repository): bool
    {
        $annotationReader = new AnnotationReader();
        $reflection = new ReflectionClass($repository);
        $attributes = $reflection->getAttributes();
        $find = false;
        foreach ($attributes as $attribute)
        {
            if ($attribute->getName() != Trashable::class) {
                continue;
            }

            $find = true;
            break;
        }

        return $find;
    }
}
