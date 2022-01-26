<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Memo;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_memo_trash")
 */
class MemoRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Memo::class);
    }

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('n');
        $query        = $queryBuilder->innerJoin('n.refuser', 'u');
        $query->where(
            'n.state LIKE :state'
        );
        $query->andWhere('n.dateStart >= now()');
        $query->orderBy('n.dateStart', 'ASC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        $query->setMaxResults(1);

        return $query->getQuery()->getResult();
    }
}
