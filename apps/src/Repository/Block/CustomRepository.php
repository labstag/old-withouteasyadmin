<?php

namespace Labstag\Repository\Block;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Custom;
use Labstag\Lib\ServiceEntityRepositoryLib;

class CustomRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Custom::class);
    }

    public function formType()
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->leftJoin('c.block', 'b');
        $queryBuilder->where('b.deletedAt IS NULL');
        $queryBuilder->andWhere('b.region IS NOT NULL');

        return $queryBuilder;
    }
}
