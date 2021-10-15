<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Category;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_category_trash")
 */
class CategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllParentForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where('a.parent IS NULL');

        return $this->setQuery($query, $get);
    }

    public function findByBookmark()
    {
        $entityManager = $this->getEntityManager();
        $dql           = $entityManager->createQueryBuilder();
        $dql->select('a');
        $dql->from(Category::class, 'a');
        $dql->leftJoin('a.bookmarks', 'b');
        $dql->innerjoin('b.refuser', 'u');
        $dql->where('b.state LIKE :state');
        $dql->setParameters(
            ['state' => '%publie%']
        );

        return $dql->getQuery()->getResult();
    }

    public function findByPost()
    {
        $entityManager = $this->getEntityManager();
        $dql           = $entityManager->createQueryBuilder();
        $dql->select('a');
        $dql->from(Category::class, 'a');
        $dql->leftJoin('a.posts', 'p');
        $dql->innerjoin('p.refuser', 'u');
        $dql->where('p.state LIKE :state');
        $dql->setParameters(
            ['state' => '%publie%']
        );

        return $dql->getQuery()->getResult();
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

    public function findTrashParentForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where('a.deletedAt IS NOT NULL');
        $query->andwhere('a.parent IS NULL');

        return $this->setQuery($query, $get);
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
