<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\ServiceEntityRepositoryLib;

class LayoutRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Layout::class);
    }

    public function findByCustom(Custom $custom): mixed
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->leftJoin('a.custom', 'c');
        $queryBuilder->where('c.id = :customid');
        $queryBuilder->setParameter('customid', $custom->getId());

        return $queryBuilder->getQuery()->getResult();
    }
}
