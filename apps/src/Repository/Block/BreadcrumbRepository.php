<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Breadcrumb;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BreadcrumbRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Breadcrumb::class);
    }
}
