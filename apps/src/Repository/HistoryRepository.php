<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\History;
use Labstag\Lib\ServiceEntityRepositoryLib;

#[Trashable(url: 'admin_history_trash')]
class HistoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, History::class);
    }

    public function findPublier(): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->innerjoin('p.refuser', 'u');
        $query->leftJoin('p.chapters', 'c');
        $query->where('p.state LIKE :state');
        $query->andWhere('c.state LIKE :state');
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );
        $query->orderBy('p.published', 'DESC');

        return $query->getQuery();
    }

    public function findPublierUsername(string $username): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->leftJoin('p.refuser', 'u');
        $query->leftJoin('p.chapters', 'c');
        $query->where('p.state LIKE :state');
        $query->andWhere('c.state LIKE :state');
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
