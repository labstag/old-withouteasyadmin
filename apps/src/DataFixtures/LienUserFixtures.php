<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\LienUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class LienUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    const NUMBER = 25;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $indexUser = $faker->numberBetween(1, 3);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addLink($faker, $user, $manager);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
