<?php

namespace Labstag\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use ReflectionClass;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TrashService
{
    public function __construct(
        protected $repositories,
        protected ManagerRegistry $manager,
        protected CsrfTokenManagerInterface $csrfTokenManager
    )
    {
    }

    public function all()
    {
        $data = [];
        foreach ($this->repositories as $repository) {
            $isTrashable = $this->isTrashable($repository::class);
            if (!$isTrashable) {
                continue;
            }

            $entity     = $repository->getClassName();
            $repository = $this->manager->getRepository($entity);
            $trash      = $repository->findTrashForAdmin([]);
            $result     = $trash->getQuery()->getResult();
            $test       = is_countable($result) ? count($result) : 0;
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
        $reader     = new AnnotationReader();
        $properties = [];
        if (!$this->isTrashable($repository)) {
            return $properties;
        }

        $reflection = $this->setReflection($repository);
        $properties = $reader->getClassAnnotations($reflection);

        return $properties[0];
    }

    public function isTrashable(string $repository): bool
    {
        $reader     = new AnnotationReader();
        $reflection = $this->setReflection($repository);
        $annotation = $reader->getClassAnnotation($reflection, Trashable::class);

        return !is_null($annotation);
    }

    protected function setReflection(string $repository): ReflectionClass
    {
        return new ReflectionClass($repository);
    }
}
