<?php

namespace Labstag\Repository;

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
        $query        = $queryBuilder->innerJoin('a.bookmarks', 'b');
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
}
