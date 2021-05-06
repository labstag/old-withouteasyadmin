<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Lib\FixtureLib;

class EmailUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker = Factory::create('fr_FR');
        $users   = $this->installService->getData('user');
        for ($index = 0; $index < self::NUMBER_EMAIL; ++$index) {
            $indexUser = $faker->numberBetween(0, count($users)-1);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addEmail($faker, $user);
        }
    }

    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }
}
