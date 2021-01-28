<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;

class PhoneUserRepository extends PhoneRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneUser::class);
    }

    public function findEnable(User $user)
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

    public function findAllForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt=:userDeleteAt AND a.deletedAt=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }

    public function findTrashForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt!=:userDeleteAt OR a.deletedAt!=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }
}
