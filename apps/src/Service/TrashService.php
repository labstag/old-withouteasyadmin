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

        $reflection = $this->setReflection($repository);
        $properties = $annotationReader->getClassAnnotations($reflection);

        return $properties[0];
    }

    public function isTrashable(string $repository): bool
    {
        $annotationReader = new AnnotationReader();
        $reflection = $this->setReflection($repository);
        $trashable = $annotationReader->getClassAnnotation($reflection, Trashable::class);

        return !is_null($trashable);
    }

    protected function setReflection(string $repository): ReflectionClass
    {
        return new ReflectionClass($repository);
    }
}
