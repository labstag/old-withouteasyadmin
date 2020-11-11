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
use Labstag\Repository\GroupeRepository;


/**
 * @codeCoverageIgnore
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private GroupeRepository $groupeRepository;

    public function __construct(GroupeRepository $groupeRepository)
    {
        $this->groupeRepository = $groupeRepository;
    }

    private function getUsers(): array
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'enable'   => true,
                'verif'    => true,
                'email'    => 'admin@email.fr',
                'groupe'   => 'admin',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'enable'   => true,
                'verif'    => true,
                'email'    => 'superadmin@email.fr',
                'groupe'   => 'superadmin',
            ],
            [
                'username' => 'disable',
                'password' => 'password',
                'enable'   => false,
                'verif'    => true,
                'email'    => 'disable@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'unverif',
                'password' => 'password',
                'enable'   => false,
                'verif'    => false,
                'email'    => 'unverif@email.fr',
                'groupe'   => 'user',
            ],
        ];

        return $users;
    }

    public function load(ObjectManager $manager)
    {
        $faker   = Factory::create('fr_FR');
        $users   = $this->getUsers();
        $groupes = $this->groupeRepository->findAll();
        // $product = new Product();
        // $manager->persist($product);

        foreach ($users as $user) {
            $this->addUser($groupes, $faker, $user, $manager);
        }
    }

    private function addEmail(
        User $user,
        string $adresse,
        ObjectManager $manager
    ): void
    {
        $email = new EmailUser();
        $email->setVerif(true);
        $email->setRefuser($user);
        $email->setAdresse($adresse);
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
        $phone->setPrincipal((1 == $index));
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

    private function getGroupe(array $groupes, string $code)
    {
        foreach ($groupes as $groupe) {
            if ($groupe->getCode() == $code) {
                return $groupe;
            }
        }

        return null;
    }

    private function addUser(
        array $groupes,
        Generator $faker,
        array $dataUser,
        ObjectManager $manager
    ): void
    {
        $user = new User();
        $user->setGroupe($this->getGroupe($groupes, $dataUser['groupe']));
        $user->setEnable($dataUser['enable']);
        $user->setVerif($dataUser['verif']);
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $rand   = rand(0, 5);
        $emails = [];
        for ($i = 0; $i < $rand; $i++) {
            $emails[] = $faker->unique()->safeEmail();
        }

        foreach ($emails as $adresse) {
            $this->addEmail($user, $adresse, $manager);
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
