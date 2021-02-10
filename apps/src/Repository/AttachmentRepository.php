<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Attachment;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_attachment_trash")
 */
class AttachmentRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attachment::class);
    }
}
