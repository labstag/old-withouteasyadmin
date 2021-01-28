<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\NoteInterne;
use Labstag\Lib\ServiceEntityRepositoryLib;

class NoteInterneRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteInterne::class);
    }

    public function findAllForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt=:userDeleteAt AND a.deletedAt=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }

    public function findTrashForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt!=:userDeleteAt OR a.deletedAt!=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }
}
