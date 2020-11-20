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

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
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
