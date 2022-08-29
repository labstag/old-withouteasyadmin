<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Menu;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_menu_trash")
 */
class MenuRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findAllCode()
    {
        $query = $this->findAllCodeQuery();

        return $query->getQuery()->getResult();
    }

    public function findAllCodeQuery()
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.position=0'
        );
        $query->andWhere(
            'u.clef IS NOT NULL'
        );

        return $query;
    }
}
