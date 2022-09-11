<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Flashbag;
use Labstag\Lib\ServiceEntityRepositoryLib;

class FlashbagRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flashbag::class);
    }
}
