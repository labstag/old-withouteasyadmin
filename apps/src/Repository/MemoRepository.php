<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Memo;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_memo_trash")
 */
class MemoRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Memo::class);
    }

    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        return $this->setQuery($queryBuilder, $get);
    }

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('n');
        $query        = $queryBuilder->innerJoin('n.refuser', 'u');
        $query->where(
            'n.state LIKE :state'
        );
        $query->andWhere('n.dateStart >= now()');
        $query->orderBy('n.dateStart', 'ASC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        $query->setMaxResults(1);

        return $query->getQuery()->getResult();
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryDateStart($query, $get);
        $this->setQueryDateEnd($query, $get);
        $this->setQueryTitle($query, $get);
        $this->setQueryRefUser($query, $get);

        return $query;
    }

    protected function setQueryDateEnd(QueryBuilder &$query, array $get)
    {
        if (!isset($get['dateEnd']) || empty($get['dateEnd'])) {
            return;
        }

        $query->andWhere('DATE(a.dateEnd) = :dateEnd');
        $query->setParameter('dateEnd', $get['dateEnd']);
    }

    protected function setQueryDateStart(QueryBuilder &$query, array $get)
    {
        if (!isset($get['dateStart']) || empty($get['dateStart'])) {
            return;
        }

        $query->andWhere('DATE(a.dateStart) = :dateStart');
        $query->setParameter('dateStart', $get['dateStart']);
    }

    protected function setQueryTitle(QueryBuilder &$query, array $get)
    {
        if (!isset($get['title']) || empty($get['title'])) {
            return;
        }

        $query->andWhere('a.title LIKE :title');
        $query->setParameter('title', '%'.$get['title'].'%');
    }
}
