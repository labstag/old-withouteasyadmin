<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\EmailUser;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\LienUser;
use Labstag\Entity\User;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function getUsers(): array
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'email'    => ['admin@email.fr'],
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'email'    => ['superadmin@email.fr'],
            ],
            [
                'username' => 'disable',
                'password' => 'disable',
                'email'    => ['disable@email.fr'],
            ],
        ];

        return $users;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = $this->getUsers();
        // $product = new Product();
        // $manager->persist($product);

        foreach ($users as $user) {
            $this->addUser($faker, $user, $manager);
        }
    }

    private function addEmail(
        User $user,
        int $index,
        string $adresse,
        ObjectManager $manager
    ): void
    {
        $email = new EmailUser();
        $email->setRefuser($user);
        $email->setAdresse($adresse);
        $principal = (0 == $index) ? true : false;
        $email->setPrincipal($principal);
        $manager->persist($email);
    }

    private function addPhone(
        Generator $faker,
        User $user,
        int $index,
        ObjectManager $manager
    ): void
    {
        $number = $faker->unique()->e164PhoneNumber();
        $phone  = new PhoneUser();
        $phone->setRefuser($user);
        $phone->setNumero($number);
        $phone->setType($faker->unique()->word());
        $phone->setCountry($faker->unique()->countryCode());
        $principal = (0 == $index) ? true : false;
        $phone->setPrincipal($principal);
        $manager->persist($phone);
    }

    private function addLink(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $lien = new LienUser();
        $lien->setRefUser($user);
        $lien->setName($faker->unique()->word());
        $lien->setAdresse($faker->unique->url());
        $manager->persist($lien);
    }

    private function addAdresse(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $adresse = new AdresseUser();
        $adresse->setRefuser($user);
        $adresse->setRue($faker->unique()->streetAddress());
        $adresse->setVille($faker->unique()->city());
        $adresse->setCountry($faker->unique()->countryCode());
        $adresse->setZipcode($faker->unique()->postcode());
        $latitude  = $faker->unique()->latitude();
        $longitude = $faker->unique()->longitude();
        $gps       = $latitude.','.$longitude;
        $adresse->setGps($gps);
        $pmr = (rand(0, 1) == 0) ? true : false;
        $adresse->setPmr($pmr);
        $manager->persist($adresse);
    }

    private function addUser(
        Generator $faker,
        array $dataUser,
        ObjectManager $manager
    ): void
    {
        $user = new User();
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);

        foreach ($dataUser['email'] as $index => $adresse) {
            $this->addEmail($user, $index, $adresse, $manager);
        }

        $adresses = rand(0, 2);
        for ($index = 1; $index <= $adresses; ++$index) {
            $this->addAdresse($faker, $user, $manager);
        }

        $phones = rand(0, 2);
        for ($index = 1; $index <= $phones; ++$index) {
            $this->addPhone($faker, $user, $index, $manager);
        }

        $links = rand(0, 10);
        for ($index = 1; $index <= $links; ++$index) {
            $this->addLink($faker, $user, $manager);
        }

        $user->setEmail($dataUser['email'][0]);
        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            GroupFixtures::class,
        ];
    }
}
