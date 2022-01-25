<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\LinkUser;

/**
 * @Trashable(url="admin_linkuser_trash")
 */
class LinkUserRepository extends LinkRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkUser::class);
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryRefUser($query, $get);

        return $query;
    }
}
