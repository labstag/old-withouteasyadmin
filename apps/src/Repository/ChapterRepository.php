<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Chapter;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'admin_history_trash')]
class ChapterRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Chapter::class);
    }

    public function findChapterByHistory(
        string $history,
        string $chapter
    ): mixed {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->leftJoin('c.history', 'h');
        $queryBuilder->where('h.slug = :slughistory');
        $queryBuilder->andWhere('c.slug = :slugchapter');
        $queryBuilder->setParameters(
            [
                'slugchapter' => $chapter,
                'slughistory' => $history,
            ]
        );

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
