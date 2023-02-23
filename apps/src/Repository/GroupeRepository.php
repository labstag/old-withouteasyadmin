<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Groupe;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_groupuser_trash")
 */
class GroupeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Groupe::class);
    }

    public function findName(string $field): mixed
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where(
            'u.name LIKE :name'
        );
        $queryBuilder->setParameters(
            [
                'name' => '%'.$field.'%',
            ]
        );

        return $queryBuilder->getQuery()->getResult();
    }
}
