<?php

namespace Labstag\Repository\Paragraph;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Paragraph\Text;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @extends ServiceEntityRepository<Text>
 *
 * @method null|Text find($id, $lockMode = null, $lockVersion = null)
 * @method null|Text findOneBy(array $criteria, array $orderBy = null)
 * @method Text[]    findAll()
 * @method Text[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Text::class);
    }
}
