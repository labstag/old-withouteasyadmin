<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\PostArchive;
use Labstag\Lib\ServiceEntityRepositoryLib;

class PostArchiveRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostArchive::class);
    }
}
