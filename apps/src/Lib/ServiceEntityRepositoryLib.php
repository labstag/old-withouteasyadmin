<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\AddressUser;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\GeoCode;
use Labstag\Entity\Groupe;
use Labstag\Entity\Libelle;
use Labstag\Entity\LinkUser;
use Labstag\Entity\Memo;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\Post;
use Labstag\Entity\Template;
use Labstag\Entity\User;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add($entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

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

    public function getLimitOffsetResult($query, $limit, $offset)
    {
        $query->setMaxResults($limit ?: null);
        $query->setFirstResult($offset ?: null);

        return $query->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove($entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
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
        $this->setQueryCity($query, $get);
        $this->setQueryCommunityName($query, $get);
        $this->setQueryCountry($query, $get);
        $this->setQueryCountryCode($query, $get);
        $this->setQueryDateEnd($query, $get);
        $this->setQueryDateStart($query, $get);
        $this->setQueryEmail($query, $get);
        $this->setQueryEtape($query, $get);
        $this->setQueryName($query, $get);
        $this->setQueryPlaceName($query, $get);
        $this->setQueryPostalCode($query, $get);
        $this->setQueryProvinceName($query, $get);
        $this->setQueryPublished($query, $get);
        $this->setQueryRefCategory($query, $get);
        $this->setQueryRefGroup($query, $get);
        $this->setQueryRefUser($query, $get);
        $this->setQueryStateName($query, $get);
        $this->setQueryTitle($query, $get);

        return $query;
    }

    protected function setQueryCity(QueryBuilder &$query, array $get): void
    {
        $launch = AddressUser::class == $this->_entityName;
        if (!$launch || !isset($get['city']) || empty($get['city'])) {
            return;
        }

        $query->andWhere('a.city LIKE :city');
        $query->setParameter('city', '%'.$get['city'].'%');
    }

    protected function setQueryCommunityName(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['communityname']) || empty($get['communityname'])) {
            return;
        }

        $query->andWhere('a.communityName LIKE :communityname');
        $query->setParameter('communityname', '%'.$get['communityname'].'%');
    }

    protected function setQueryCountry(QueryBuilder &$query, array $get): void
    {
        $launch = in_array($this->_entityName, [AddressUser::class, PhoneUser::class]);
        if (!$launch || !isset($get['country']) || empty($get['country'])) {
            return;
        }

        $query->andWhere('a.country LIKE :country');
        $query->setParameter('country', '%'.$get['country'].'%');
    }

    protected function setQueryCountryCode(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['countrycode']) || empty($get['countrycode'])) {
            return;
        }

        $query->andWhere('a.countryCode LIKE :countrycode');
        $query->setParameter('countrycode', '%'.$get['countrycode'].'%');
    }

    protected function setQueryDateEnd(QueryBuilder &$query, array $get): void
    {
        $launch = Memo::class == $this->_entityName;
        if (!$launch || !isset($get['dateEnd']) || empty($get['dateEnd'])) {
            return;
        }

        $query->andWhere('DATE(a.dateEnd) = :dateEnd');
        $query->setParameter('dateEnd', $get['dateEnd']);
    }

    protected function setQueryDateStart(QueryBuilder &$query, array $get): void
    {
        $launch = Memo::class == $this->_entityName;
        if (!$launch || !isset($get['dateStart']) || empty($get['dateStart'])) {
            return;
        }

        $query->andWhere('DATE(a.dateStart) = :dateStart');
        $query->setParameter('dateStart', $get['dateStart']);
    }

    protected function setQueryEmail(QueryBuilder &$query, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['email']) || empty($get['email'])) {
            return;
        }

        $query->andWhere('a.email = :email');
        $query->setParameter('email', $get['email']);
    }

    protected function setQueryEtape(QueryBuilder &$query, array $get): void
    {
        $entities = [
            Bookmark::class,
            Edito::class,
            EmailUser::class,
            Memo::class,
            PhoneUser::class,
            Post::class,
            User::class,
        ];
        $launch   = in_array($this->_entityName, $entities);
        if ($launch || !isset($get['etape']) || empty($get['etape'])) {
            return;
        }

        $query->andWhere('a.state LIKE :state');
        $query->setParameter('state', '%'.$get['etape'].'%');
    }

    protected function setQueryName(QueryBuilder &$query, array $get): void
    {
        $entities = [
            Bookmark::class,
            Category::class,
            Groupe::class,
            Libelle::class,
            Template::class,
        ];
        $launch   = in_array($this->_entityName, $entities);
        if (!$launch || !isset($get['name']) || empty($get['name'])) {
            return;
        }

        $query->andWhere('a.name LIKE :name');
        $query->setParameter('name', '%'.$get['name'].'%');
    }

    protected function setQueryPlaceName(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['placename']) || empty($get['placename'])) {
            return;
        }

        $query->andWhere('a.placeName LIKE :placename');
        $query->setParameter('placename', '%'.$get['placename'].'%');
    }

    protected function setQueryPostalCode(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['postalcode']) || empty($get['postalcode'])) {
            return;
        }

        $query->andWhere('a.postalCode LIKE :postalcode');
        $query->setParameter('postalcode', '%'.$get['postalcode'].'%');
    }

    protected function setQueryProvinceName(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['provincename']) || empty($get['provincename'])) {
            return;
        }

        $query->andWhere('a.provinceName LIKE :provincename');
        $query->setParameter('provincename', '%'.$get['provincename'].'%');
    }

    protected function setQueryPublished(QueryBuilder &$query, array $get): void
    {
        $launch = in_array($this->_entityName, [Edito::class, Post::class]);
        if (!$launch || !isset($get['published']) || empty($get['published'])) {
            return;
        }

        $query->andWhere('DATE(a.published) = :published');
        $query->setParameter('published', $get['published']);
    }

    protected function setQueryRefCategory(QueryBuilder &$query, array $get): void
    {
        $launch = in_array($this->_entityName, [Bookmark::class, Post::class]);
        if (!$launch || !isset($get['refcategory']) || empty($get['refcategory'])) {
            return;
        }

        $query->leftJoin('a.refcategory', 'u');
        $query->andWhere('u.id = :refcategory');
        $query->setParameter('refcategory', $get['refcategory']);
    }

    protected function setQueryRefGroup(QueryBuilder &$query, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['refgroup']) || empty($get['refgroup'])) {
            return;
        }

        $query->leftJoin('a.refgroupe', 'g');
        $query->andWhere('g.id = :refgroup');
        $query->setParameter('refgroup', $get['refgroup']);
    }

    protected function setQueryRefUser(QueryBuilder &$query, array $get): void
    {
        $entities = [
            AddressUser::class,
            Bookmark::class,
            Edito::class,
            EmailUser::class,
            GeoCode::class,
            LinkUser::class,
            Memo::class,
            PhoneUser::class,
            Post::class,
        ];
        $launch   = in_array($this->_entityName, $entities);
        if (!$launch || !isset($get['refuser']) || empty($get['refuser'])) {
            return;
        }

        $query->leftJoin('a.refuser', 'u');
        $query->andWhere('u.id = :refuser');
        $query->setParameter('refuser', $get['refuser']);
    }

    protected function setQueryStateName(QueryBuilder &$query, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['statename']) || empty($get['statename'])) {
            return;
        }

        $query->andWhere('a.stateName LIKE :statename');
        $query->setParameter('statename', '%'.$get['statename'].'%');
    }

    protected function setQueryTitle(QueryBuilder &$query, array $get): void
    {
        $launch = in_array($this->_entityName, [Edito::class, Memo::class, Post::class]);
        if (!$launch || !isset($get['title']) || empty($get['title'])) {
            return;
        }

        $query->andWhere('a.title LIKE :title');
        $query->setParameter('title', '%'.$get['title'].'%');
    }

    protected function setQueryUsername(QueryBuilder &$query, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['username']) || empty($get['username'])) {
            return;
        }

        $query->andWhere('a.username LIKE :username');
        $query->setParameter('username', '%'.$get['username'].'%');
    }
}
