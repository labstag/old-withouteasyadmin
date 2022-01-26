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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function findName(string $field)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.name LIKE :name'
        );
        $query->setParameters(
            [
                'name' => '%'.$field.'%',
            ]
        );

        return $query->getQuery()->getResult();
    }
}
