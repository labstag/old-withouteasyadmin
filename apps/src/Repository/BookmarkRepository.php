<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Bookmark;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_bookmark_trash")
 */
class BookmarkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->innerjoin('p.refuser', 'u');
        $query->where(
            'p.state LIKE :state'
        );
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery();
    }

    public function findPublierCategory($code)
    {
        $queryBuilder = $this->createQueryBuilder('b');
        $query        = $queryBuilder->where('b.state LIKE :state');
        $query->orderBy('b.published', 'DESC');
        $query->leftJoin('b.refcategory', 'c');
        $query->andWhere('c.slug=:slug');
        $query->setParameters(
            [
                'slug'  => $code,
                'state' => '%publie%',
            ]
        );

        return $query->getQuery();
    }

    public function findPublierLibelle($code)
    {
        $queryBuilder = $this->createQueryBuilder('b');
        $query        = $queryBuilder->where('b.state LIKE :state');
        $query->orderBy('b.published', 'DESC');
        $query->leftJoin('b.libelles', 'l');
        $query->andWhere('l.slug=:slug');
        $query->setParameters(
            [
                'slug'  => $code,
                'state' => '%publie%',
            ]
        );

        return $query->getQuery();
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryName($query, $get);
        $this->setQueryRefUser($query, $get);
        $this->setQueryRefCategory($query, $get);

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

    protected function setQueryName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['name']) || empty($get['name'])) {
            return;
        }

        $query->andWhere('a.name LIKE :name');
        $query->setParameter('name', '%'.$get['name'].'%');
    }

    protected function setQueryRefCategory(QueryBuilder &$query, array $get)
    {
        if (!isset($get['refcategory']) || empty($get['refcategory'])) {
            return;
        }

        $query->leftJoin('a.refcategory', 'u');
        $query->andWhere('u.id = :refcategory');
        $query->setParameter('refcategory', $get['refcategory']);
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
}
