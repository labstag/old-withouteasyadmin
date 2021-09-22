<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\NoteInterne;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_noteinterne_trash")
 */
class NoteInterneRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteInterne::class);
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

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('n');
        $query = $queryBuilder->innerJoin('n.refuser', 'u');
        $query->where(
            'n.state LIKE :state'
        );
        $query->andWhere('n.dateDebut >= now()');
        $query->orderBy('n.dateDebut', 'ASC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        $query->setMaxResults(1);

        return $query->getQuery()->getResult();
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
