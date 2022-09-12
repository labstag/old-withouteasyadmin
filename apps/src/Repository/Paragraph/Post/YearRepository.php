<?php

namespace Labstag\Repository\Paragraph\Post;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Post\Year;
use Labstag\Lib\ServiceEntityRepositoryLib;

class YearRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Year::class);
    }
}
