<?php

namespace Labstag\Repository\Paragraph\Post;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Post\Header;
use Labstag\Lib\ServiceEntityRepositoryLib;

class HeaderRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Header::class);
    }
}
