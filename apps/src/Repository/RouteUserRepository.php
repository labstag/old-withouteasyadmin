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

    public function findRoute(User $user, string $route): mixed
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->leftJoin(
            'a.user',
            'u'
        );
        $queryBuilder->leftJoin(
            'a.route',
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
