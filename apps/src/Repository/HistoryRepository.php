<?php

namespace Labstag\Repository;

use Labstag\Entity\History;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
/**
 * @Trashable(url="admin_history_trash")
 */
class HistoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }
}
