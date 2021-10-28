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
        $query->andWhere('n.dateDebut >= now()');
        $query->orderBy('n.dateDebut', 'ASC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        $query->setMaxResults(1);

        return $query->getQuery()->getResult();
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryDateDebut($query, $get);
        $this->setQueryDateFin($query, $get);
        $this->setQueryTitle($query, $get);
        $this->setQueryRefUser($query, $get);

        return $query;
    }

    protected function setQueryDateDebut(QueryBuilder &$query, array $get)
    {
        if (!isset($get['dateDebut']) || empty($get['dateDebut'])) {
            return;
        }

        $query->andWhere('DATE(a.dateDebut) = :dateDebut');
        $query->setParameter('dateDebut', $get['dateDebut']);
    }

    protected function setQueryDateFin(QueryBuilder &$query, array $get)
    {
        if (!isset($get['dateFin']) || empty($get['dateFin'])) {
            return;
        }

        $query->andWhere('DATE(a.dateFin) = :dateFin');
        $query->setParameter('dateFin', $get['dateFin']);
    }

    protected function setQueryEtape(QueryBuilder &$query, array $get)
    {
        if (!isset($get['etape']) || empty($get['etape'])) {
            return;
        }

        $query->andWhere('a.state LIKE :state');
        $query->setParameter('state', '%'.$get['etape'].'%');
    }

    protected function setQueryRefUser(QueryBuilder &$query, array $get)
    {
        if (!isset($get['refuser']) || empty($get['refuser'])) {
            return;
        }

        $query->leftJoin('a.refuser', 'u');
        $query->andWhere('u.id = :refuser');
        $query->setParameter('refuser', $get['refuser']);
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
