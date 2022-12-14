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
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Category::class);
    }

    public function findAllParentForAdmin(array $get): QueryBuilder
    {
        $query = $this->createQueryBuilder('a');
        $query->where('a.parent IS NULL');

        return $this->setQuery($query, $get);
    }

    public function findByBookmark()
    {
        $query = $this->createQueryBuilder('a');
        $query->leftJoin('a.bookmarks', 'b');
        $query->where('b.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findByPost()
    {
        $query = $this->createQueryBuilder('a');
        $query->leftJoin('a.posts', 'p');
        $query->innerJoin('p.refuser', 'u');
        $query->where('p.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findName(string $field)
    {
        $query = $this->createQueryBuilder('u');
        $query->where(
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
        $query = $this->createQueryBuilder('a');
        $query->where('a.deletedAt IS NOT NULL');
        $query->andwhere('a.parent IS NULL');

        return $this->setQuery($query, $get);
    }
}
