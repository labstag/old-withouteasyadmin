<?php

namespace Labstag\Repository;

use Labstag\Entity\LienUser;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LienUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienUser[]    findAll()
 * @method LienUser[]    findBy(
 *  array $criteria,
 *  array $orderBy = null,
 *  $limit = null,
 *  $offset = null
 * )
 */
class LienUserRepository extends LienRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienUser::class);
    }

    // /**
    //  * @return LienUser[] Returns an array of LienUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LienUser
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
