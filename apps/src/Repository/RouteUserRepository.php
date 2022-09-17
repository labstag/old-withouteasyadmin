<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RouteUser::class);
    }

    public function findRoute(User $user, string $route)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $queryBuilder->leftJoin(
            'a.refroute',
            'r'
        );
        $queryBuilder->where(
            'u.id=:uid AND r.name=:route'
        );
        $queryBuilder->setParameters(
            [
                'uid'   => $user->getId(),
                'route' => $route,
            ]
        );

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
