<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\PostLibelle;
use Labstag\Lib\ServiceEntityRepositoryLib;

class PostLibelleRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLibelle::class);
    }
}
