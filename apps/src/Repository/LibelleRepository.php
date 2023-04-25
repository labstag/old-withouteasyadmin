<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Libelle;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'admin_libelle_trash')]
class LibelleRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Libelle::class);
    }

    public function findByBookmark(): mixed
    {
        $query = $this->createQueryBuilder('a');
        $query->innerJoin('a.bookmarks', 'b');
        $query->where('b.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findByPost(): mixed
    {
        $query = $this->createQueryBuilder('a');
        $query->innerJoin('a.posts', 'p');
        $query->innerjoin('p.user', 'u');
        $query->where('p.state LIKE :state');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findName(string $field): mixed
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
}
