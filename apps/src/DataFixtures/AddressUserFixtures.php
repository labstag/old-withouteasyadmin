<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\{
    DependentFixtureInterface as DependentInterface
};
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\AddressUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class AddressUserFixtures extends FixtureLib implements DependentInterface
{
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker = $this->setFaker();
        $users = $this->installService->getData('user');
        for ($index = 0; $index < self::NUMBER_ADRESSE; ++$index) {
            $indexUser = $faker->numberBetween(0, count($users) - 1);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addAddress($faker, $user);
        }
    }

    protected function addAddress(
        Generator $faker,
        User $user
    ): void
    {
        $address = new AddressUser();
        $old     = clone $address;
        $address->setRefuser($user);
        $address->setStreet($faker->streetAddress);
        $address->setCity($faker->city);
        $address->setCountry($faker->countryCode);
        $address->setZipcode($faker->postcode);
        $address->setType($faker->unique()->colorName);
        $latitude  = $faker->latitude;
        $longitude = $faker->longitude;
        $gps       = $latitude.','.$longitude;
        $address->setGps($gps);
        $address->setPmr((bool) rand(0, 1));
        $this->addressUserRH->handle($old, $address);
    }
}
