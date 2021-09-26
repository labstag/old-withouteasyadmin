<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Bookmark;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_bookmark_trash")
 */
class BookmarkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }
}
