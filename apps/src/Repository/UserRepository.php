<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

#[Trashable(url: 'admin_user_trash')]
class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    public function findOauth(
        string $identity,
        string $name
    ): mixed {
        $query = $this->createQueryBuilder('u');
        $query->leftJoin('u.oauthConnectUsers', 'o');
        $query->where('o.name = :name');
        $query->andWhere('o.identity=:identity');
        $query->setParameters(
            [
                'name'     => $name,
                'identity' => $identity,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findUserEnable(string $field): ?User
    {
        $query = $this->createQueryBuilder('u');
        $query->where(
            'u.username=:username OR u.email=:email'
        );
        $query->andWhere('u.state LIKE :state1 OR u.state LIKE :state2');
        $query->setParameters(
            [
                'state1'   => '%valider%',
                'state2'   => '%lostpassword%',
                'username' => $field,
                'email'    => $field,
            ]
        );

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findUserName(string $field): mixed
    {
        $query = $this->createQueryBuilder('u');
        $query->where(
            'u.username LIKE :username OR u.email LIKE :email'
        );
        $query->setParameters(
            [
                'username' => '%'.$field.'%',
                'email'    => '%'.$field.'%',
            ]
        );

        return $query->getQuery()->getResult();
    }
}
