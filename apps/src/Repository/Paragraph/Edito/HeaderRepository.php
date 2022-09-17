<?php

namespace Labstag\Repository\Paragraph\Edito;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Edito\Header;
use Labstag\Lib\ServiceEntityRepositoryLib;

class HeaderRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Header::class);
    }
}
