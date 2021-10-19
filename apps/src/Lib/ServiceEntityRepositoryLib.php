<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{
    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        return $this->setQuery($queryBuilder, $get);
    }

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandom()
    {
        $name          = $this->getClassMetadataName();
        $dql           = 'SELECT p FROM '.$name.' p ORDER BY RAND()';
        $entityManager = $this->getEntityManager();
        $query         = $entityManager->createQuery($dql);
        $query         = $query->setMaxResults(1);

        return $query->getOneOrNullResult();
    }

    public function findTrashForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $query = $queryBuilder->where(
            'a.deletedAt IS NOT NULL'
        );

        return $this->setQuery($query, $get);
    }

    protected function getClassMetadataName(): string
    {
        $methods = get_class_methods($this);
        $name    = '';
        if (in_array('getClassMetadata', $methods)) {
            $name = $this->getClassMetadata()->getName();
        }

        return $name;
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        unset($get);

        return $query;
    }
}
