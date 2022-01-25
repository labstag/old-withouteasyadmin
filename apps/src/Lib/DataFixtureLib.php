<?php
namespace Labstag\Lib;

use Labstag\DataFixtures\DataFixtures;

abstract class DataFixtureLib extends FixtureLib
{

    public function getDependencies()
    {
        return [DataFixtures::class];
    }
}
