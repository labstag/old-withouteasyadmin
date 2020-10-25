<?php

namespace Labstag\Repository;

use Labstag\Entity\PhoneUser;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhoneUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneUser[]    findAll()
 * @method PhoneUser[]    findBy(
 *  array $criteria,
 *  array $orderBy = null,
 *  $limit = null,
 *  $offset = null
 * )
 */
class PhoneUserRepository extends PhoneRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneUser::class);
    }

    // /**
    //  * @return PhoneUser[] Returns an array of PhoneUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhoneUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
