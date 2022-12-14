<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\LinkUser;

/**
 * @Trashable(url="admin_linkuser_trash")
 */
class LinkUserRepository extends LinkRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, LinkUser::class);
    }
}
