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
}
