<?php

namespace Labstag\Repository\Paragraph\History;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\History\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }
}
