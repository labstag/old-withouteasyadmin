<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class PhoneUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    const NUMBER = 25;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $indexUser = $faker->numberBetween(1, 3);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addPhone($faker, $user, $manager);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function addPhone(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $number = $faker->unique()->e164PhoneNumber();
        $phone  = new PhoneUser();
        $phone->setRefuser($user);
        $phone->setNumero($number);
        $phone->setType($faker->unique()->word());
        $phone->setCountry($faker->unique()->countryCode());
        $manager->persist($phone);
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
