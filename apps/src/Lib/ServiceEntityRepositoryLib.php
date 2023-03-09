<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\AddressUser;
use Labstag\Entity\Block;
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
    public function add(mixed $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function findAllForAdmin(array $get): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        return $this->setQuery($queryBuilder, $get);
    }

    public function findEnableByGroupe(?Groupe $groupe = null): mixed
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $parameters = ['state' => 1];
        if (!is_null($groupe)) {
            $query->andWhere('a.groupe=:refgroupe');
            $parameters['refgroupe'] = $groupe;
        }

        $query->setParameters($parameters);

        return $query->getQuery()->getResult();
    }

    public function findEnableByUser(?User $user = null): mixed
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query        = $queryBuilder->where(
            'a.state=:state'
        );
        $parameters = ['state' => 1];
        if (!is_null($user)) {
            $query->andWhere('a.user=:refuser');
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
        $classMetadataName = $this->getClassMetadataName();
        $dql               = 'SELECT p FROM '.$classMetadataName.' p ORDER BY RAND()';
        $entityManager     = $this->getEntityManager();
        $query             = $entityManager->createQuery($dql);
        $query             = $query->setMaxResults(1);

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

    public function getLimitOffsetResult(
        Query $query,
        ?int $limit = null,
        ?int $offset = null
    ): mixed
    {
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);

        return $query->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(mixed $entity): void
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

    protected function setQuery(QueryBuilder $queryBuilder, array $get): QueryBuilder
    {
        $functions = [
            'setQueryCity',
            'setQueryCommunityName',
            'setQueryCountry',
            'setQueryCountryCode',
            'setQueryDateEnd',
            'setQueryDateStart',
            'setQueryEmail',
            'setQueryEtape',
            'setQueryName',
            'setQueryPlaceName',
            'setQueryPostalCode',
            'setQueryProvinceName',
            'setQueryPublished',
            'setQueryRefCategory',
            'setQueryRefGroup',
            'setQueryRefUser',
            'setQueryStateName',
            'setQueryTitle',
        ];

        foreach ($functions as $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            call_user_func_array($callable, [$queryBuilder, $get]);
        }

        return $queryBuilder;
    }

    protected function setQueryCity(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = AddressUser::class == $this->_entityName;
        if (!$launch || !isset($get['city']) || empty($get['city'])) {
            return;
        }

        $queryBuilder->andWhere('a.city LIKE :city');
        $queryBuilder->setParameter('city', '%'.$get['city'].'%');
    }

    protected function setQueryCommunityName(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['communityname']) || empty($get['communityname'])) {
            return;
        }

        $queryBuilder->andWhere('a.communityName LIKE :communityname');
        $queryBuilder->setParameter('communityname', '%'.$get['communityname'].'%');
    }

    protected function setQueryCountry(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = in_array($this->_entityName, [AddressUser::class, PhoneUser::class]);
        if (!$launch || !isset($get['country']) || empty($get['country'])) {
            return;
        }

        $queryBuilder->andWhere('a.country LIKE :country');
        $queryBuilder->setParameter('country', '%'.$get['country'].'%');
    }

    protected function setQueryCountryCode(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['countrycode']) || empty($get['countrycode'])) {
            return;
        }

        $queryBuilder->andWhere('a.countryCode LIKE :countrycode');
        $queryBuilder->setParameter('countrycode', '%'.$get['countrycode'].'%');
    }

    protected function setQueryDateEnd(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = Memo::class == $this->_entityName;
        if (!$launch || !isset($get['dateEnd']) || empty($get['dateEnd'])) {
            return;
        }

        $queryBuilder->andWhere('DATE(a.dateEnd) = :dateEnd');
        $queryBuilder->setParameter('dateEnd', $get['dateEnd']);
    }

    protected function setQueryDateStart(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = Memo::class == $this->_entityName;
        if (!$launch || !isset($get['dateStart']) || empty($get['dateStart'])) {
            return;
        }

        $queryBuilder->andWhere('DATE(a.dateStart) = :dateStart');
        $queryBuilder->setParameter('dateStart', $get['dateStart']);
    }

    protected function setQueryEmail(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['email']) || empty($get['email'])) {
            return;
        }

        $queryBuilder->andWhere('a.email = :email');
        $queryBuilder->setParameter('email', $get['email']);
    }

    protected function setQueryEtape(QueryBuilder $queryBuilder, array $get): void
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
        $launch = in_array($this->_entityName, $entities);
        if ($launch || !isset($get['etape']) || empty($get['etape'])) {
            return;
        }

        $queryBuilder->andWhere('a.state LIKE :state');
        $queryBuilder->setParameter('state', '%'.$get['etape'].'%');
    }

    protected function setQueryName(QueryBuilder $queryBuilder, array $get): void
    {
        $entities = [
            Bookmark::class,
            Category::class,
            Groupe::class,
            Libelle::class,
            Template::class,
        ];
        $launch = in_array($this->_entityName, $entities);
        if (!$launch || !isset($get['name']) || empty($get['name'])) {
            return;
        }

        $queryBuilder->andWhere('a.name LIKE :name');
        $queryBuilder->setParameter('name', '%'.$get['name'].'%');
    }

    protected function setQueryPlaceName(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['placename']) || empty($get['placename'])) {
            return;
        }

        $queryBuilder->andWhere('a.placeName LIKE :placename');
        $queryBuilder->setParameter('placename', '%'.$get['placename'].'%');
    }

    protected function setQueryPostalCode(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['postalcode']) || empty($get['postalcode'])) {
            return;
        }

        $queryBuilder->andWhere('a.postalCode LIKE :postalcode');
        $queryBuilder->setParameter('postalcode', '%'.$get['postalcode'].'%');
    }

    protected function setQueryProvinceName(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['provincename']) || empty($get['provincename'])) {
            return;
        }

        $queryBuilder->andWhere('a.provinceName LIKE :provincename');
        $queryBuilder->setParameter('provincename', '%'.$get['provincename'].'%');
    }

    protected function setQueryPublished(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = in_array($this->_entityName, [Edito::class, Post::class]);
        if (!$launch || !isset($get['published']) || empty($get['published'])) {
            return;
        }

        $queryBuilder->andWhere('DATE(a.published) = :published');
        $queryBuilder->setParameter('published', $get['published']);
    }

    protected function setQueryRefCategory(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = in_array($this->_entityName, [Bookmark::class, Post::class]);
        if (!$launch || !isset($get['refcategory']) || empty($get['refcategory'])) {
            return;
        }

        $queryBuilder->leftJoin('a.category', 'u');
        $queryBuilder->andWhere('u.id = :refcategory');
        $queryBuilder->setParameter('refcategory', $get['refcategory']);
    }

    protected function setQueryRefGroup(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['groupe']) || empty($get['groupe'])) {
            return;
        }

        $queryBuilder->leftJoin('a.groupe', 'g');
        $queryBuilder->andWhere('g.id = :refgroup');
        $queryBuilder->setParameter('refgroup', $get['groupe']);
    }

    protected function setQueryRefUser(QueryBuilder $queryBuilder, array $get): void
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
        $launch = in_array($this->_entityName, $entities);
        if (!$launch || !isset($get['refuser']) || empty($get['refuser'])) {
            return;
        }

        $queryBuilder->leftJoin('a.user', 'u');
        $queryBuilder->andWhere('u.id = :refuser');
        $queryBuilder->setParameter('refuser', $get['refuser']);
    }

    protected function setQueryStateName(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = GeoCode::class == $this->_entityName;
        if (!$launch || !isset($get['statename']) || empty($get['statename'])) {
            return;
        }

        $queryBuilder->andWhere('a.stateName LIKE :statename');
        $queryBuilder->setParameter('statename', '%'.$get['statename'].'%');
    }

    protected function setQueryTitle(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = in_array($this->_entityName, [Block::class, Edito::class, Memo::class, Post::class]);
        if (!$launch || !isset($get['title']) || empty($get['title'])) {
            return;
        }

        $queryBuilder->andWhere('a.title LIKE :title');
        $queryBuilder->setParameter('title', '%'.$get['title'].'%');
    }

    protected function setQueryUsername(QueryBuilder $queryBuilder, array $get): void
    {
        $launch = User::class == $this->_entityName;
        if (!$launch || !isset($get['username']) || empty($get['username'])) {
            return;
        }

        $queryBuilder->andWhere('a.username LIKE :username');
        $queryBuilder->setParameter('username', '%'.$get['username'].'%');
    }
}
