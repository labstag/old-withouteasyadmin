<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\AdresseUser;
use Doctrine\ORM\Query;

class AdresseUserRepository extends AdresseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseUser::class);
    }

    public function findAllForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.id IS NOT NULL'
        );

        return $query->getQuery();
    }

    public function findTrashForAdmin(): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt IS NOT NULL OR a.deletedAt IS NOT NULL'
        );

        return $query->getQuery()->getResult();
    }
}
