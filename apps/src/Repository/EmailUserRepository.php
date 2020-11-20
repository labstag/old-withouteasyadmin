<?php
namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;

class EmailUserRepository extends EmailRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailUser::class);
    }

    public function getEmailsUserVerif(User $user, bool $verif)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.refuser=:user AND u.verif=:verif'
        );
        $query->setParameters(
            [
                'user'  => $user,
                'verif' => $verif,
            ]
        );

        return $query->getQuery()->getResult();
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
