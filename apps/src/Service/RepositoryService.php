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

    public function get(string $repository): ?ServiceEntityRepositoryLib
    {
        $return = null;
        foreach ($this->rewindableGenerator as $repo) {
            if ($repo->getClassName() == $repository) {
                $return = $repo;

                break;
            }
        }

        return $return;
    }
}
