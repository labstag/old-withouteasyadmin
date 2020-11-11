<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Edito;
use Labstag\Entity\User;


/**
 * @codeCoverageIgnore
 */
class EditoFixtures extends Fixture implements DependentFixtureInterface
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

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function addEdito(
        $users,
        Generator $faker,
        int $index,
        ObjectManager $manager
    ): void
    {
        $edito = new Edito();
        $edito->setTitle($faker->unique()->text(rand(5, 50)));
        $enable = ($index == 0) ? true : false;
        $edito->setEnable($enable);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $edito->setContent(str_replace("\n\n", '<br />', $content));
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $manager->persist($edito);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
