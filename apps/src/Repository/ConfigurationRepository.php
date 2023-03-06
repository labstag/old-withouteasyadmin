<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Configuration;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ConfigurationRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Configuration::class);
    }
}
