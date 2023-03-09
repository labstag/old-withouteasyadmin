<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Attachment;
use Labstag\Lib\ServiceEntityRepositoryLib;

#[Trashable(url: 'admin_attachment_trash')]
class AttachmentRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Attachment::class);
    }

    public function getFavicon(): ?Attachment
    {
        /** @var ?Attachment $data */
        return $this->findOneBy(['code' => 'favicon']);
    }

    public function getImageDefault(): ?Attachment
    {
        /** @var ?Attachment $data */
        return $this->findOneBy(['code' => 'image']);
    }
}
