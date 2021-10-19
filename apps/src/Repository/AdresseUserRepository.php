<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\AdresseUser;

/**
 * @Trashable(url="admin_adresseuser_trash")
 */
class AdresseUserRepository extends AdresseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseUser::class);
    }

    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->leftJoin('a.refuser', 'u');
        $query->where(
            'u.id IS NOT NULL'
        );

        return $this->setQuery($query, $get);
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryCountry($query, $get);
        $this->setQueryVille($query, $get);
        $this->setQueryRefUser($query, $get);

        return $query;
    }

    protected function setQueryCountry(QueryBuilder &$query, array $get)
    {
        if (!isset($get['country']) || empty($get['country'])) {
            return;
        }

        $query->andWhere('a.country LIKE :country');
        $query->setParameter('country', '%'.$get['country'].'%');
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

    protected function setQueryVille(QueryBuilder &$query, array $get)
    {
        if (!isset($get['ville']) || empty($get['ville'])) {
            return;
        }

        $query->andWhere('a.ville LIKE :ville');
        $query->setParameter('ville', '%'.$get['ville'].'%');
    }
}
