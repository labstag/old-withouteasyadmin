<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ServiceEntityRepositoryLib;

class WorkflowUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowUser::class);
    }
}
