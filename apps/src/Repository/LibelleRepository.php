<?php

namespace Labstag\Repository;

use Labstag\Entity\Libelle;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Lib\ServiceEntityRepositoryLib;

class LibelleRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libelle::class);
    }
}
