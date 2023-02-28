<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;

#[Trashable(url: 'admin_emailuser_trash')]
class EmailUserRepository extends EmailRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, EmailUser::class);
    }

    public function getEmailsUserVerif(User $user, bool $verif): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where(
            'u.refuser=:user AND u.state LIKE :state'
        );
        $queryBuilder->setParameters(
            [
                'user'  => $user,
                'state' => $verif ? '%valide%' : '%averifier%',
            ]
        );

        return $queryBuilder->getQuery()->getResult();
    }
}
