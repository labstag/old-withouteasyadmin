<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Footer;
use Labstag\Lib\ServiceEntityRepositoryLib;

class FooterRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Footer::class);
    }
}
