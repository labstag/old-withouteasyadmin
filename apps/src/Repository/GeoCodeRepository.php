<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\GeoCode;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_geocode_trash")
 */
class GeoCodeRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoCode::class);
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryCountryCode($query, $get);
        $this->setQueryPostalCode($query, $get);
        $this->setQueryPlaceName($query, $get);
        $this->setQueryStateName($query, $get);
        $this->setQueryProvinceName($query, $get);
        $this->setQueryCommunityName($query, $get);
        $this->setQueryRefUser($query, $get);

        return $query;
    }

    protected function setQueryCommunityName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['communityname']) || empty($get['communityname'])) {
            return;
        }

        $query->andWhere('a.communityName LIKE :communityname');
        $query->setParameter('communityname', '%'.$get['communityname'].'%');
    }

    protected function setQueryCountryCode(QueryBuilder &$query, array $get)
    {
        if (!isset($get['countrycode']) || empty($get['countrycode'])) {
            return;
        }

        $query->andWhere('a.countryCode LIKE :countrycode');
        $query->setParameter('countrycode', '%'.$get['countrycode'].'%');
    }

    protected function setQueryPlaceName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['placename']) || empty($get['placename'])) {
            return;
        }

        $query->andWhere('a.placeName LIKE :placename');
        $query->setParameter('placename', '%'.$get['placename'].'%');
    }

    protected function setQueryPostalCode(QueryBuilder &$query, array $get)
    {
        if (!isset($get['postalcode']) || empty($get['postalcode'])) {
            return;
        }

        $query->andWhere('a.postalCode LIKE :postalcode');
        $query->setParameter('postalcode', '%'.$get['postalcode'].'%');
    }

    protected function setQueryProvinceName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['provincename']) || empty($get['provincename'])) {
            return;
        }

        $query->andWhere('a.provinceName LIKE :provincename');
        $query->setParameter('provincename', '%'.$get['provincename'].'%');
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

    protected function setQueryStateName(QueryBuilder &$query, array $get)
    {
        if (!isset($get['statename']) || empty($get['statename'])) {
            return;
        }

        $query->andWhere('a.stateName LIKE :statename');
        $query->setParameter('statename', '%'.$get['statename'].'%');
    }
}
