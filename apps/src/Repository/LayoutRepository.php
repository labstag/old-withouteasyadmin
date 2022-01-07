<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Layout;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method null|Layout find($id, $lockMode = null, $lockVersion = null)
 * @method null|Layout findOneBy(array $criteria, array $orderBy = null)
 * @method Layout[]    findAll()
 * @method Layout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LayoutRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Layout::class);
    }
}
