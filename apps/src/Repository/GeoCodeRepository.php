<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\GeoCode;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'gestion_geocode_trash')]
class GeoCodeRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, GeoCode::class);
    }
}
