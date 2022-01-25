<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
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
        $queryBuilder = $this->createQueryBuilder('e');
        $query        = $queryBuilder->leftjoin('e.refuser', 'u');
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

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryPublished($query, $get);
        $this->setQueryTitle($query, $get);
        $this->setQueryRefUser($query, $get);

        return $query;
    }
}
