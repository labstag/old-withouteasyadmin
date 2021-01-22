<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Lib\FixtureLib;

class PhoneUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    const NUMBER = 25;

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker     = Factory::create('fr_FR');
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $indexUser = $faker->numberBetween(1, 3);
            $stateId   = array_rand($statesTab);
            $states    = $statesTab[$stateId];
            $user      = $this->getReference('user_' . $indexUser);
            $this->addPhone($faker, $user, $states);
        }
    }

    private function getStates()
    {
        return [
            ['submit'],
            [
                'submit',
                'valider',
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            CacheFixtures::class,
            UserFixtures::class,
        ];
    }
}
