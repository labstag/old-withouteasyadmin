<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Header;
use Labstag\Lib\ServiceEntityRepositoryLib;

class HeaderRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Header::class);
    }
}
