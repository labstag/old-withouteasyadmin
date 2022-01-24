<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

abstract class CommandLib extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }
}
