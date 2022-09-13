<?php

namespace Labstag\Repository\Paragraph\Edito;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Edito\Header;
use Labstag\Lib\ServiceEntityRepositoryLib;

class HeaderRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Header::class);
    }
}
