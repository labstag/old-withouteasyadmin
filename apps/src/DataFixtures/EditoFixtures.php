<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\UserRepository;

class EditoFixtures extends FixtureLib implements DependentFixtureInterface
{
    public const NUMBER = 25;

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $users = $this->userRepository->findAll();
        $faker = Factory::create('fr_FR');
        /** @var resource $finfo */
        $states = $this->getStates();
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $stateId = array_rand($states);
            $state   = $states[$stateId];
            $this->addEdito($users, $faker, $index, $state);
        }
    }

    private function getStates()
    {
        return [
            'brouillon',
            'relecture',
            'publie',
            'rejete',
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
