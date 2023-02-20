<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\TextImage;
use Labstag\Lib\ServiceEntityRepositoryLib;

class TextImageRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, TextImage::class);
    }
}
