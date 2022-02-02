<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\AddressUser;

/**
 * @Trashable(url="admin_addressuser_trash")
 */
class AddressUserRepository extends AddressRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressUser::class);
    }
}
