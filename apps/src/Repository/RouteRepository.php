<?php

namespace Labstag\Repository;

use Labstag\Entity\Route;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findLost(array $routes)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.name NOT IN (:routes)'
        );
        $query->setParameters(
            ['routes' => $routes]
        );

        return $query->getQuery()->getResult();
    }
}
