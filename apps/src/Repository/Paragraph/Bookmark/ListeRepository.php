<?php

namespace Labstag\Repository\Paragraph\Bookmark;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Bookmark\Liste;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ListeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liste::class);
    }
}
