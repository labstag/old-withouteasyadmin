<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Custom;
use Labstag\Lib\ServiceEntityRepositoryLib;

class CustomRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Custom::class);
    }

    public function formType()
    {
        $query = $this->createQueryBuilder('c');
        $query->leftJoin('c.block', 'b');
        $query->where('b.deletedAt IS NULL');
        $query->andWhere('b.region IS NOT NULL');

        return $query;
    }
}
