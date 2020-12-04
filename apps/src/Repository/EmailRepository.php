<?php

namespace Labstag\Repository;

use Labstag\Lib\ServiceEntityRepositoryLib;

abstract class EmailRepository extends ServiceEntityRepositoryLib
{

    /**
     * Get random data.
     *
     * @return object
     */
    public function findOneRandomToVerif($state)
    {
        $name          = $this->getClassMetadata()->getName();
        $dql           = 'SELECT p FROM ' . $name . ' p WHERE p.verif='.$state.' ORDER BY RAND()';
        $entityManager = $this->getEntityManager();
        $query         = $entityManager->createQuery($dql);
        $query         = $query->setMaxResults(1);
        $result        = $query->getOneOrNullResult();

        return $result;
    }
}
