<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{
    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        return $this->setQuery($queryBuilder, $get);
    }

    public function findEnableByGroupe(?Groupe $groupe = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $parameters   = ['state' => 1];
        if (!is_null($groupe)) {
            $query->andWhere('a.refgroupe=:refgroupe');
            $parameters['refgroupe'] = $groupe;
        }

        $query->setParameters($parameters);

        return $query->getQuery()->getResult();
    }

    public function findEnableByUser(?User $user = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $parameters   = ['state' => 1];
        if (!is_null($user)) {
            $query->andWhere('a.refuser=:refuser');
            $parameters['refuser'] = $user;
        }

        $query->setParameters($parameters);

        return $query->getQuery()->getResult();
    }

    /**
     * Get random data.
     */
    public function findOneRandom(): object
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

    protected function setQueryCountry(QueryBuilder &$query, array $get)
    {
        if (!isset($get['country']) || empty($get['country'])) {
            return;
        }

        $query->andWhere('a.country LIKE :country');
        $query->setParameter('country', '%'.$get['country'].'%');
    }

    protected function setQueryEtape(QueryBuilder &$query, array $get)
    {
        if (!isset($get['etape']) || empty($get['etape'])) {
            return;
        }

        $query->andWhere('a.state LIKE :state');
        $query->setParameter('state', '%'.$get['etape'].'%');
    }

    protected function setQueryName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['name']) || empty($get['name'])) {
            return;
        }

        $query->andWhere('a.name LIKE :name');
        $query->setParameter('name', '%'.$get['name'].'%');
    }

    protected function setQueryPublished(QueryBuilder &$query, array $get)
    {
        if (!isset($get['published']) || empty($get['published'])) {
            return;
        }

        $query->andWhere('DATE(a.published) = :published');
        $query->setParameter('published', $get['published']);
    }

    protected function setQueryRefCategory(QueryBuilder &$query, array $get)
    {
        if (!isset($get['refcategory']) || empty($get['refcategory'])) {
            return;
        }

        $query->leftJoin('a.refcategory', 'u');
        $query->andWhere('u.id = :refcategory');
        $query->setParameter('refcategory', $get['refcategory']);
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

    protected function setQueryTitle(QueryBuilder &$query, array $get)
    {
        if (!isset($get['title']) || empty($get['title'])) {
            return;
        }

        $query->andWhere('a.title LIKE :title');
        $query->setParameter('title', '%'.$get['title'].'%');
    }
}
