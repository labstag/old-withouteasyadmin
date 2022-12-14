<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Command\Command;

abstract class CommandLib extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function getRepository(string $entity): EntityRepository
    {
        return $this->entityManager->getRepository($entity);
    }
}
