<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;

class EmailUserRepository extends EmailRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailUser::class);
    }

    public function getEmailsUserVerif(User $user, bool $verif): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
            'u.refuser=:user AND u.state LIKE :state'
        );
        $query->setParameters(
            [
                'user'  => $user,
                'state' => $verif ? 'valide' : 'averifier',
            ]
        );

        return $query->getQuery()->getResult();
    }

    public function findAllForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt=:userDeleteAt AND a.deletedAt=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }

    public function findTrashForAdmin(): Query
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.deletedAt!=:userDeleteAt OR a.deletedAt!=:adresseDeleteAt'
        );
        $query->setParameters(
            [
                'userDeleteAt'    => '',
                'adresseDeleteAt' => '',
            ]
        );

        return $query->getQuery();
    }
}
