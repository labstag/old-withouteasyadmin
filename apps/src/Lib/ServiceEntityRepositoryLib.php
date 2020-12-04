<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandom()
    {
        $name          = $this->getClassMetadata()->getName();
        $dql           = 'SELECT p FROM ' . $name . ' p ORDER BY RAND()';
        $entityManager = $this->getEntityManager();
        $query         = $entityManager->createQuery($dql);
        $query         = $query->setMaxResults(1);
        $result        = $query->getOneOrNullResult();

        return $result;
    }

    public function findAllForAdmin(): Query
    {
        $name          = $this->getClassMetadata()->getName();
        $dql           = 'SELECT a FROM ' . $name . ' a';
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery($dql);
    }
}
