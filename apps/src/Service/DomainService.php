<?php

namespace Labstag\Service;

use Labstag\Lib\DomainLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class DomainService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator
    )
    {
    }

    public function getDomain(string $entity): ?DomainLib
    {
        $return = null;
        foreach ($this->rewindableGenerator as $domain) {
            if ($domain->getEntity() == $entity) {
                $return = $domain;

                break;
            }
        }

        return $return;
    }

    public function getTitles(): array
    {
        $titles = [];
        foreach ($this->rewindableGenerator as $domain) {
            $titles = array_merge($titles, $domain->getTitles());
        }

        return $titles;
    }
}
