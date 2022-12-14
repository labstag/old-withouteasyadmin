<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Route;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Route::class);
    }

    public function findLost(array $routes)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where(
            'u.name NOT IN (:routes)'
        );
        $queryBuilder->setParameters(
            ['routes' => $routes]
        );

        return $queryBuilder->getQuery()->getResult();
    }
}
