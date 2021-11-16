<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Page;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_page_trash")
 */
class PageRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }
}
