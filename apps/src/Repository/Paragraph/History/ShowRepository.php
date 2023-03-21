<?php

namespace Labstag\Repository\Paragraph\History;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\History\Show;
use Labstag\Lib\RepositoryLib;

class ShowRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Show::class);
    }
}
