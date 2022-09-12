<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Route;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findLost(array $routes)
    {
        $query = $this->createQueryBuilder('u');
        $query->where(
            'u.name NOT IN (:routes)'
        );
        $query->setParameters(
            ['routes' => $routes]
        );

        return $query->getQuery()->getResult();
    }
}
