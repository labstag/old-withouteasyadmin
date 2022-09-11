<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\BookmarkCategory;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BookmarkCategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookmarkCategory::class);
    }
}
