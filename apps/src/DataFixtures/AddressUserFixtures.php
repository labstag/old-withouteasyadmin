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
        $this->loadForeachUser(self::NUMBER_ADRESSE, 'addAddress');
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
        $address->setPmr((bool) random_int(0, 1));
        $this->addressUserRH->handle($old, $address);
    }
}
