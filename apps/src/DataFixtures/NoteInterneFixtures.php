<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\{
    DependentFixtureInterface as DependentInterface
};
use Doctrine\Persistence\ObjectManager;
use Labstag\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

/**
 * @codeCoverageIgnore
 */
class NoteInterneFixtures extends FixtureLib implements DependentInterface
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
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addNoteInterne($users, $faker, $index, $manager, $maxDate);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
