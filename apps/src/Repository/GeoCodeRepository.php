<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\GeoCode;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_geocode_trash")
 */
class GeoCodeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, GeoCode::class);
    }
}
