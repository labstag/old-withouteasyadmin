<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Labstag\Lib\FixtureLib;
use Symfony\Contracts\Cache\CacheInterface;

class CacheFixtures extends FixtureLib
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function load(ObjectManager $manager): void
    {
        $this->cache->clear();
    }
}
