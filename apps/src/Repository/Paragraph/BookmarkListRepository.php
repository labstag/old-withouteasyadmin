<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\BookmarkList;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BookmarkListRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookmarkList::class);
    }
}
