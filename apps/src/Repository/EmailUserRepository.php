<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;

/**
 * @Trashable(url="admin_emailuser_trash")
 */
class EmailUserRepository extends EmailRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailUser::class);
    }

    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin(
            'a.refuser',
            'u'
        );
        $query->where(
            'u.id IS NOT NULL'
        );

        return $this->setQuery($query, $get);
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
                'state' => $verif ? '%valide%' : '%averifier%',
            ]
        );

        return $query->getQuery()->getResult();
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryRefUser($query, $get);

        return $query;
    }

    protected function setQueryRefUser(QueryBuilder &$query, array $get)
    {
        if (!isset($get['refuser']) || empty($get['refuser'])) {
            return;
        }

        $query->leftJoin('a.refuser', 'u');
        $query->andWhere('u.id = :refuser');
        $query->setParameter('refuser', $get['refuser']);
    }
}
