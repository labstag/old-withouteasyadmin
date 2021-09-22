<?php

namespace Labstag\Repository;

use Labstag\Entity\Bookmark;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Doctrine\Persistence\ManagerRegistry;

class BookmarkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }
}
