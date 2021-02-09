<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Lib\ServiceEntityRepositoryLib;

class WorkflowGroupeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowGroupe::class);
    }
}
