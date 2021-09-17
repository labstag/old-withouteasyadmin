<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Category;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_category_trash")
 */
class CategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
