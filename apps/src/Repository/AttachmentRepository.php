<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Attachment;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'gestion_attachment_trash')]
class AttachmentRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Attachment::class);
    }

    public function getFavicon(): ?Attachment
    {
        $data = $this->getByCode('favicon');
        if ($data instanceof Attachment) {
            return $data;
        }

        return null;
    }

    public function getImageDefault(): ?Attachment
    {
        $data = $this->getByCode('image');
        if ($data instanceof Attachment) {
            return $data;
        }

        return null;
    }

    private function getByCode(string $code): mixed
    {
        return $this->findOneBy(['code' => $code]);
    }
}
