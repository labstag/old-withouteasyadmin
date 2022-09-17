<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Groupe;
use Labstag\Entity\RouteGroupe;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteGroupeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RouteGroupe::class);
    }

    public function findRoute(Groupe $groupe, string $route)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->leftJoin(
            'a.refgroupe',
            'g'
        );
        $queryBuilder->leftJoin(
            'a.refroute',
            'r'
        );
        $queryBuilder->where(
            'g.id=:gid AND r.name=:route'
        );
        $queryBuilder->setParameters(
            [
                'gid'   => $groupe->getId(),
                'route' => $route,
            ]
        );

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
