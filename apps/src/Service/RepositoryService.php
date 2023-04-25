<?php

namespace Labstag\Service;

use Labstag\Lib\RepositoryLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class RepositoryService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator
    ) {
    }

    public function get(string $entity): ?RepositoryLib
    {
        $return = null;
        foreach ($this->rewindableGenerator as $repository) {
            /** @var RepositoryLib $repository */
            if ($repository->getClassName() == $entity) {
                $return = $repository;

                break;
            }
        }

        return $return;
    }
}
