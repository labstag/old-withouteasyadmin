<?php

namespace Labstag\Service;

use Labstag\Lib\ServiceEntityRepositoryLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class RepositoryService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator
    )
    {
    }

    public function get(string $entity): ?ServiceEntityRepositoryLib
    {
        $return = null;
        foreach ($this->rewindableGenerator as $repository) {
            /** @var ServiceEntityRepositoryLib $repository */
            if ($repository->getClassName() == $entity) {
                $return = $repository;

                break;
            }
        }

        return $return;
    }
}
