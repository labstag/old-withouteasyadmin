<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\History;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_history_trash")
 */
class HistoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->innerjoin('p.refuser', 'u');
        $query->where(
            'p.state LIKE :state'
        );
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );
        $query->orderBy('p.published', 'DESC');

        return $query->getQuery();
    }

    public function findPublierUsername($username)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->leftJoin('p.refuser', 'u');
        $query        = $query->where('p.state LIKE :state');
        $query->andWhere('u.username = :username');
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            [
                'state'    => '%publie%',
                'username' => $username,
            ]
        );
        $query->orderBy('p.published', 'DESC');

        return $query->getQuery();
    }
}
