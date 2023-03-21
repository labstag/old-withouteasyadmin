<?php

namespace Labstag\Repository\Paragraph\Post;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Post\Liste;
use Labstag\Lib\RepositoryLib;

class ListeRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Liste::class);
    }
}
