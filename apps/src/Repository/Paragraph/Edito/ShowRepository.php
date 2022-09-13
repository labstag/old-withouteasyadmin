<?php

namespace Labstag\Repository\Paragraph\Edito;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Edito\Show;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ShowRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Show::class);
    }
}
