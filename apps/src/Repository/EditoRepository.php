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

    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.id IS NOT NULL'
        );

        return $this->setQuery($query, $get);
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

    protected function setQueryEtape(QueryBuilder &$query, array $get)
    {
        if (!isset($get['etape']) || empty($get['etape'])) {
            return;
        }

        $query->andWhere('a.state LIKE :state');
        $query->setParameter('state', '%'.$get['etape'].'%');
    }

    protected function setQueryPublished(QueryBuilder &$query, array $get)
    {
        if (!isset($get['published']) || empty($get['published'])) {
            return;
        }

        $query->andWhere('DATE(a.published) = :published');
        $query->setParameter('published', $get['published']);
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
