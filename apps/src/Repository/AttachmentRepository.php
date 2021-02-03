<?php

namespace Labstag\Repository;

use Labstag\Entity\Attachment;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Lib\ServiceEntityRepositoryLib;

class AttachmentRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attachment::class);
    }
}
