<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
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
        $query = $this->createQueryBuilder('p');
        $id    = $options['data']->getId();
        if (empty($id)) {
            return $query;
        }

        $query->where(
            'p.id != :id'
        );
        $query->setParameters(
            ['id' => $id]
        );

        return $query;
    }
}
