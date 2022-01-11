<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Page;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_page_trash")
 */
class PageRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function formType(array $options)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $id           = $options['data']->getId();
        if (empty($id)) {
            return $queryBuilder;
        }

        $query = $queryBuilder->where(
            'p.id != :id'
        );
        $query->setParameters(
            ['id' => $id]
        );

        return $query;
    }
}
