<?php

namespace Labstag\Repository\Paragraph\Edito;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Edito\Show;
use Labstag\Lib\RepositoryLib;

class ShowRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Show::class);
    }
}
