<?php

namespace Labstag\Repository\Paragraph\Bookmark;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Bookmark\Category;
use Labstag\Lib\ServiceEntityRepositoryLib;

class CategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Category::class);
    }
}
