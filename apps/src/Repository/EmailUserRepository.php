<?php

namespace Labstag\Repository;

use Labstag\Entity\EmailUser;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailUser[]    findAll()
 * @method EmailUser[]    findBy(
 *  array $criteria,
 *  array $orderBy = null,
 *  $limit = null,
 *  $offset = null
 * )
 */
class EmailUserRepository extends EmailRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailUser::class);
    }

    // /**
    //  * @return EmailUser[] Returns an array of EmailUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailUser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
