<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\PhoneUser;

#[Trashable(url: 'gestion_phoneuser_trash')]
class PhoneUserRepository extends PhoneRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, PhoneUser::class);
    }
}
