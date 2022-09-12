<?php

namespace Labstag\Repository\Paragraph\History;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\History\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
