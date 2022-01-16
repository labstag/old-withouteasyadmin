<?php

namespace Labstag\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TrashService
{
    public function __construct(
        protected ManagerRegistry $manager,
        protected CsrfTokenManagerInterface $csrfTokenManager
    )
    {
    }

    public function all()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Repository');
        $data = [];
        foreach ($finder as $file) {
            $repositoryFile = 'Labstag\\Repository\\'.$file->getFilenameWithoutExtension();
            $isTrashable    = $this->isTrashable($repositoryFile);
            if (!$isTrashable) {
                continue;
            }

            $entity     = str_replace(
                'Repository',
                '',
                'Labstag\\Entity\\'.$file->getFilenameWithoutExtension()
            );
            $repository = $this->manager->getRepository($entity);
            $trash      = $repository->findTrashForAdmin([]);
            $result     = $trash->getQuery()->getResult();
            $test       = is_countable($result) ? count($result) : 0;
            if (0 == $test) {
                continue;
            }

            $data[] = [
                'name'       => strtolower(
                    str_replace(
                        'Repository',
                        '',
                        $file->getFilenameWithoutExtension()
                    )
                ),
                'properties' => $this->getProperties($repositoryFile),
                'entity'     => $entity,
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
