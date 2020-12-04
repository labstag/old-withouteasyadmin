<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\{
    DependentFixtureInterface as DependentInterface
};
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Lib\FixtureLib;

class NoteInterneFixtures extends FixtureLib implements DependentInterface
{
    public const NUMBER = 25;

    public function load(ObjectManager $manager): void
    {
        $users   = $this->userRepository->findAll();
        $faker   = Factory::create('fr_FR');
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addNoteInterne($users, $faker, $index, $manager, $maxDate);
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
