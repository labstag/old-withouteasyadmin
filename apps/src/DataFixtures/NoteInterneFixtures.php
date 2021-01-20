<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Lib\FixtureLib;

class NoteInterneFixtures extends FixtureLib implements DependentFixtureInterface
{
    public const NUMBER = 25;

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $users   = $this->userRepository->findAll();
        $faker   = Factory::create('fr_FR');
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addNoteInterne($users, $faker, $index, $maxDate);
        }
    }

    public function getDependencies()
    {
        return [
            CacheFixtures::class,
            UserFixtures::class,
        ];
    }
}
