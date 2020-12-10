<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandomToLost($state)
    {
        $name = $this->getClassMetadataName();
        $dql           = 'SELECT p FROM ' . $name . ' p WHERE p.lost='.$state.' ORDER BY RAND()';
        $entityManager = $this->getEntityManager();
        $query         = $entityManager->createQuery($dql);
        $query         = $query->setMaxResults(1);
        $result        = $query->getOneOrNullResult();

        return $result;
    }

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandomToVerif($state)
    {
        $name = $this->getClassMetadataName();
        $dql           = 'SELECT p FROM ' . $name . ' p WHERE p.verif='.$state.' ORDER BY RAND()';
        $entityManager = $this->getEntityManager();
        $query         = $entityManager->createQuery($dql);
        $query         = $query->setMaxResults(1);
        $result        = $query->getOneOrNullResult();

        return $result;
    }

    public function findUserEnable(string $field): ?User
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.username=:username OR u.email=:email'
        );
        $query->andWhere('u.enable=true');
        $query->andWhere('u.verif=true');
        $query->andWhere('u.lost=false');
        $query->setParameters(
            [
                'username' => $field,
                'email'    => $field,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }
}
