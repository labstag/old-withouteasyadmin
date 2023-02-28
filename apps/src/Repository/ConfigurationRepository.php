<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Configuration;
use Labstag\Lib\ServiceEntityRepositoryLib;

#[Trashable(url: 'admin_configuration_trash')]
class ConfigurationRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Configuration::class);
    }
}
