<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Chapter;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_history_trash")
 */
class ChapterRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function findChapterByHistory($history, $chapter)
    {
        $query = $this->createQueryBuilder('c');
        $query->leftJoin('c.refhistory', 'h');
        $query->where('h.slug = :slughistory');
        $query->andWhere('c.slug = :slugchapter');
        $query->setParameters(
            [
                'slugchapter' => $chapter,
                'slughistory' => $history,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }
}
