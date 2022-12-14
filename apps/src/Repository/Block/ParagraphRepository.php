<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Paragraph;
use Labstag\Lib\ServiceEntityRepositoryLib;

class ParagraphRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Paragraph::class);
    }
}
