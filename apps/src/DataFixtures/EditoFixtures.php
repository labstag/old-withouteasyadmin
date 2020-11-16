<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

/**
 * @codeCoverageIgnore
 */
class EditoFixtures extends FixtureLib implements DependentFixtureInterface
{
    const NUMBER = 25;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $faker = Factory::create('fr_FR');
        /** @var resource $finfo */
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addEdito($users, $faker, $index, $manager);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
