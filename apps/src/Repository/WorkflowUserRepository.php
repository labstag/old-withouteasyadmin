<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ServiceEntityRepositoryLib;

class WorkflowUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, WorkflowUser::class);
    }
}
