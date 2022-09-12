<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\ServiceEntityRepositoryLib;

class LayoutRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Layout::class);
    }

    public function findByCustom(Custom $custom)
    {
        $query = $this->createQueryBuilder('a');
        $query->leftJoin('a.custom', 'c');
        $query->where('c.id = :customid');
        $query->setParameter('customid', $custom->getId());

        return $query->getQuery()->getResult();
    }
}
