<?php

namespace Labstag\Repository\Paragraph\Bookmark;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Bookmark\Category;
use Labstag\Lib\ServiceEntityRepositoryLib;

class CategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
