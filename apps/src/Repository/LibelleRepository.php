<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Libelle;
use Labstag\Lib\ServiceEntityRepositoryLib;

class LibelleRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libelle::class);
    }

    public function findByPost()
    {
        $entityManager = $this->getEntityManager();
        $dql           = $entityManager->createQueryBuilder();
        $dql->select('a');
        $dql->from(Libelle::class, 'a');
        $dql->innerJoin('a.posts', 'p');
        $dql->where('p.state LIKE :state');
        $dql->setParameters(
            ['state' => '%publie%']
        );

        return $dql->getQuery()->getResult();
    }

    public function findNom(string $field)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.nom LIKE :nom'
        );
        $query->setParameters(
            [
                'nom' => '%'.$field.'%',
            ]
        );

        return $query->getQuery()->getResult();
    }
}
