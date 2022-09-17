<?php

namespace Labstag\Lib;

use Labstag\DataFixtures\DataFixtures;

abstract class DataFixtureLib extends FixtureLib
{
    /**
     * @return array<class-string<DataFixtures>>
     */
    public function getDependencies(): array
    {
        return [DataFixtures::class];
    }
}
