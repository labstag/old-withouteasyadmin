<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\User;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ServiceEntityRepositoryLib;

class WorkflowUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowUser::class);
    }

    public function findEnable(?User $user = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $parameters   = ['state' => 1];
        if (!is_null($user)) {
            $query->andWhere('a.refuser=:refuser');
            $parameters['refuser'] = $user;
        }

        $query->setParameters($parameters);

        return $query->getQuery()->getResult();
    }
}
