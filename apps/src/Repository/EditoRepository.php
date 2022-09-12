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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edito::class);
    }

    public function findOnePublier()
    {
        $query = $this->createQueryBuilder('e');
        $query->leftjoin('e.refuser', 'u');
        $query->where(
            'e.state LIKE :state'
        );
        $query->orderBy('e.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        $query->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }
}
