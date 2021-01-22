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
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addEdito($users, $faker, $index, $states);
        }
    }

    private function getStates()
    {
        return [
            ['submit'],
            [
                'submit',
                'relire',
            ],
            [
                'submit',
                'relire',
                'corriger',
            ],
            [
                'submit',
                'relire',
                'publier',
            ],
            [
                'submit',
                'relire',
                'rejeter',
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
