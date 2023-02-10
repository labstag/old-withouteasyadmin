<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Video;
use Labstag\Lib\ServiceEntityRepositoryLib;

class VideoRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Video::class);
    }
}
