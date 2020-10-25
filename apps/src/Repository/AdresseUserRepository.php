<?php

namespace Labstag\Repository;

use Labstag\Entity\AdresseUser;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdresseUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseUser[]    findAll()
 * @method Adresse[]    findBy(
 *  array $criteria,
 *  array $orderBy = null,
 *  $limit = null,
 *  $offset = null
 * )
 */
class AdresseUserRepository extends AdresseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseUser::class);
    }

    // /**
    //  * @return AdresseUser[] Returns an array of AdresseUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdresseUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
