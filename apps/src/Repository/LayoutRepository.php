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

    public function findByUrlAndCustom(Custom $custom, string $url)
    {
        $query = $this->createQueryBuilder('a');
        $query = $query->where(
            $query->expr()->like(
                'a.url',
                $query->expr()->literal('%'.$url.'%')
            )
        );
        $query = $query->andWhere('a.custom = :custom');
        $query = $query->setParameter('custom', $custom);

        return $query->getQuery()->getResult();
    }
}
