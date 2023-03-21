<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Lib\RepositoryLib;

class WorkflowGroupeRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, WorkflowGroupe::class);
    }
}
