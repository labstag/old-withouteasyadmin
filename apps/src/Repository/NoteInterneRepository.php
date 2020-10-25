<?php

namespace Labstag\Repository;

use Labstag\Entity\NoteInterne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoteInterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteInterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteInterne[]    findAll()
 * @method NoteInterne[]    findBy(
 *  array $criteria,
 *  array $orderBy = null,
 *  $limit = null,
 *  $offset = null
 * )
 */
class NoteInterneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteInterne::class);
    }

    // /**
    //  * @return NoteInterne[] Returns an array of NoteInterne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoteInterne
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
