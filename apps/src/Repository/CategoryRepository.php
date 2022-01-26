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
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin('a.bookmarks', 'b');
        $query->where('b.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findByPost()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin('a.posts', 'p');
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

    public function findTrashParentForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where('a.deletedAt IS NOT NULL');
        $query->andwhere('a.parent IS NULL');

        return $this->setQuery($query, $get);
    }
}
