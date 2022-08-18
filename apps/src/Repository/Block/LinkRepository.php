<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Link;
use Labstag\Lib\ServiceEntityRepositoryLib;

class LinkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }
}
