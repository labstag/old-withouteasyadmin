<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\{
    DependentFixtureInterface as DependentInterface
};
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class AdresseUserFixtures extends FixtureLib implements DependentInterface
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
            $this->addAdresse($faker, $user);
        }
    }

    protected function addAdresse(
        Generator $faker,
        User $user
    ): void {
        $adresse = new AdresseUser();
        $old     = clone $adresse;
        $adresse->setRefuser($user);
        $adresse->setRue($faker->streetAddress);
        $adresse->setVille($faker->city);
        $adresse->setCountry($faker->countryCode);
        $adresse->setZipcode($faker->postcode);
        $adresse->setType($faker->unique()->colorName);
        $latitude  = $faker->latitude;
        $longitude = $faker->longitude;
        $gps       = $latitude.','.$longitude;
        $adresse->setGps($gps);
        $adresse->setPmr((bool) rand(0, 1));
        $this->adresseUserRH->handle($old, $adresse);
    }
}
