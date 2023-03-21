<?php

namespace Labstag\Repository\Paragraph\Edito;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Edito\Header;
use Labstag\Lib\RepositoryLib;

class HeaderRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Header::class);
    }
}
