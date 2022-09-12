<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Groupe;
use Labstag\Entity\RouteGroupe;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteGroupeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteGroupe::class);
    }

    public function findRoute(Groupe $groupe, string $route)
    {
        $query = $this->createQueryBuilder('a');
        $query->leftJoin(
            'a.refgroupe',
            'g'
        );
        $query->leftJoin(
            'a.refroute',
            'r'
        );
        $query->where(
            'g.id=:gid AND r.name=:route'
        );
        $query->setParameters(
            [
                'gid'   => $groupe->getId(),
                'route' => $route,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }
}
