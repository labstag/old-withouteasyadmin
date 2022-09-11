<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\BookmarkLibelle;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BookmarkLibelleRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookmarkLibelle::class);
    }
}
