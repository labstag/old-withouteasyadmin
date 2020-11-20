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

    public function findUserEnable(string $field)
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
