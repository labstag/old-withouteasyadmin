<?php

namespace Labstag\Repository;

use Labstag\Entity\RouteGroupe;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method RouteGroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteGroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteGroupe[]    findAll()
 * @method RouteGroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteGroupeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteGroupe::class);
    }

    public function findRoute(Groupe $groupe, string $route)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
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

        return $query->getQuery()->getResult();
    }

    public function findEnable()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $query->setParameters(
            ['state' => 1]
        );

        return $query->getQuery()->getResult();
    }
}
