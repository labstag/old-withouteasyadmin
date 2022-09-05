<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\HistoryList;
use Labstag\Lib\ServiceEntityRepositoryLib;

class HistoryListRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryList::class);
    }
}
