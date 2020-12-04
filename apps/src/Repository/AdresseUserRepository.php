<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\AdresseUser;

class AdresseUserRepository extends AdresseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseUser::class);
    }
}
