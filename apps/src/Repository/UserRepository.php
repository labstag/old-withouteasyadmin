<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_user_trash")
 */
class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserEnable(string $field): ?User
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
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

    public function findUserName(string $field)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query        = $queryBuilder->where(
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

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryUsername($query, $get);
        $this->setQueryEmail($query, $get);
        $this->setQueryRefGroup($query, $get);

        return $query;
    }

    protected function setQueryEmail(QueryBuilder &$query, array $get)
    {
        if (!isset($get['email']) || empty($get['email'])) {
            return;
        }

        $query->andWhere('a.email = :email');
        $query->setParameter('email', $get['email']);
    }

    protected function setQueryRefGroup(QueryBuilder &$query, array $get)
    {
        if (!isset($get['refgroup']) || empty($get['refgroup'])) {
            return;
        }

        $query->leftJoin('a.refgroupe', 'g');
        $query->andWhere('g.id = :refgroup');
        $query->setParameter('refgroup', $get['refgroup']);
    }

    protected function setQueryUsername(QueryBuilder &$query, array $get)
    {
        if (!isset($get['username']) || empty($get['username'])) {
            return;
        }

        $query->andWhere('a.username LIKE :username');
        $query->setParameter('username', '%'.$get['username'].'%');
    }
}
