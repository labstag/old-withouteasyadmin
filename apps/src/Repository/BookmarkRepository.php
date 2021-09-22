<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Bookmark;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BookmarkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }
}
