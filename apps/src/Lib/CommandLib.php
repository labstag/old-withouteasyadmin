<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Labstag\Service\RepositoryService;
use Symfony\Component\Console\Command\Command;

abstract class CommandLib extends Command
{
    public function __construct(
        protected RepositoryService $repositoryService,
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }
}
