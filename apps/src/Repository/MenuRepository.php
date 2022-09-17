<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Menu;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_menu_trash")
 */
class MenuRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Menu::class);
    }

    public function findAllCode()
    {
        $queryBuilder = $this->findAllCodeQuery();

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllCodeQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where(
            'u.position=0'
        );
        $queryBuilder->andWhere(
            'u.clef IS NOT NULL'
        );

        return $queryBuilder;
    }
}
