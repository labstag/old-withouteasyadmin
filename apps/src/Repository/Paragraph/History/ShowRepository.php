<?php

namespace Labstag\Repository\Paragraph\History;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\History\Show;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ShowRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Show::class);
    }
}
