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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkUser::class);
    }
}
