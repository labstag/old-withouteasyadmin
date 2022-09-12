<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

class RouteUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteUser::class);
    }

    public function findRoute(User $user, string $route)
    {
        $query = $this->createQueryBuilder('a');
        $query->leftJoin(
            'a.refuser',
            'u'
        );
        $query->leftJoin(
            'a.refroute',
            'r'
        );
        $query->where(
            'u.id=:uid AND r.name=:route'
        );
        $query->setParameters(
            [
                'uid'   => $user->getId(),
                'route' => $route,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }
}
