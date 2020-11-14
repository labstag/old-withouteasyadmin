<?php

namespace Labstag\Repository;

use Labstag\Lib\ServiceEntityRepositoryLib;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\OauthConnectUser;

class OauthConnectUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OauthConnectUser::class);
    }    /**
          * @return OauthConnectUser|void
          */
    public function login(?string $identity, ?string $oauth)
    {
        if (is_null($identity) || is_null($oauth)) {
            return;
        }

        $builder = $this->createQueryBuilder('u');
        $builder->where(
            'u.name = :name AND u.identity = :identity'
        );
        $builder->setParameters(
            [
                'name'     => $oauth,
                'identity' => $identity,
            ]
        );

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function findDistinctAllOauth(): array
    {
        $builder = $this->createQueryBuilder('u');
        $builder->select('u.name');
        $builder->distinct();
        $builder->orderBy('u.name', 'ASC');

        return $builder->getQuery()->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
