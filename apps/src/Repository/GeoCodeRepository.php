<?php

namespace Labstag\Repository;

use Labstag\Entity\GeoCode;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Lib\ServiceEntityRepositoryLib;

class GeoCodeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoCode::class);
    }
}
