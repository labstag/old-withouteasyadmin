<?php

namespace Labstag\Repository\Paragraph\History;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\History\Chapter;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ChapterRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }
}
