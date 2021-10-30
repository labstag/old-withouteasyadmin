<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Libelle;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_libelle_trash")
 */
class LibelleRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libelle::class);
    }

    public function findByBookmark()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->from(Libelle::class, 'a');
        $query->innerJoin('a.bookmarks', 'b');
        $query->innerjoin('b.refuser', 'u');
        $query->where('b.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findByPost()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->innerJoin('a.posts', 'p');
        $query->innerjoin('p.refuser', 'u');
        $query->where('p.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
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

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryName($query, $get);

        return $query;
    }

    protected function setQueryName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['name']) || empty($get['name'])) {
            return;
        }

        $query->andWhere('a.name LIKE :name');
        $query->setParameter('name', '%'.$get['name'].'%');
    }
}
