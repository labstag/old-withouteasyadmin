<?php

namespace Labstag\Repository;

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

    public function findAllParentForAdmin()
    {
        $methods = get_class_methods($this);
        $name    = '';

        if (in_array('getClassMetadata', $methods)) {
            $name = $this->getClassMetadata()->getName();
        }

        $dql           = 'SELECT a FROM '.$name.' a WHERE a.parent IS NULL';
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery($dql);
    }

    public function findTrashParentForAdmin(): array
    {
        $methods = get_class_methods($this);
        $name    = '';

        if (in_array('getClassMetadata', $methods)) {
            $name = $this->getClassMetadata()->getName();
        }

        $entityManager = $this->getEntityManager();
        $dql           = $entityManager->createQueryBuilder();
        $dql->select('a');
        $dql->from($name, 'a');
        $dql->where('a.deletedAt IS NOT NULL');
        $dql->andwhere('a.parent IS NULL');

        return $dql->getQuery()->getResult();
    }
}
