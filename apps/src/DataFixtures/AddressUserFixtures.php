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
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $this->loadForeachUser(self::NUMBER_ADRESSE, 'addAddress', $objectManager);
    }

    protected function addAddress(
        Generator $generator,
        User $user,
        ObjectManager $objectManager
    ): void {
        $addressUser = new AddressUser();
        $addressUser->setRefuser($user);
        $addressUser->setStreet($generator->streetAddress);
        $addressUser->setCity($generator->city);
        $addressUser->setCountry($generator->countryCode);
        $addressUser->setZipcode($generator->postcode);
        $addressUser->setType($generator->unique()->colorName());

        $latitude  = $generator->latitude;
        $longitude = $generator->longitude;
        $gps       = $latitude.','.$longitude;
        $addressUser->setGps($gps);
        $addressUser->setPmr((bool) random_int(0, 1));

        $objectManager->persist($addressUser);
    }
}
