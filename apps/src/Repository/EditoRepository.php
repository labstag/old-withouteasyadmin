<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Edito;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_edito_trash")
 */
class EditoRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Edito::class);
    }

    public function findOnePublier(): mixed
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->leftjoin('e.refuser', 'u');
        $queryBuilder->where(
            'e.state LIKE :state'
        );
        $queryBuilder->orderBy('e.published', 'DESC');
        $queryBuilder->setParameters(
            ['state' => '%publie%']
        );

        $queryBuilder->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
