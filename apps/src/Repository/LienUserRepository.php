<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\LienUser;

class LienUserRepository extends LienRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienUser::class);
    }
}
