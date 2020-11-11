<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandom()
    {
        $name   = $this->getClassMetadata()->getName();
        $table  = substr(
            $name,
            strrpos($name, '\\') + 1
        );
        $dql    = 'SELECT p FROM Labstag:'.$table.' p ORDER BY RAND()';
        $em     = $this->getEntityManager();
        $query  = $em->createQuery($dql);
        $query  = $query->setMaxResults(1);
        $result = $query->getOneOrNullResult();

        return $result;
    }
}
