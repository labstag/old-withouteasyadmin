<?php

namespace Labstag\Lib;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\LienUser;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;

abstract class FixtureLib extends Fixture
{

    private function getGroupe(array $groupes, string $code)
    {
        foreach ($groupes as $groupe) {
            if ($groupe->getCode() == $code) {
                return $groupe;
            }
        }

        return null;
    }

    protected function addEmail(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $email = new EmailUser();
        $email->setVerif($faker->numberBetween(0, 1));
        $email->setRefuser($user);
        $email->setAdresse($faker->unique()->safeEmail());
        $manager->persist($email);
    }

    protected function addLink(
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

    protected function addNoteInterne(
        $users,
        Generator $faker,
        int $index,
        ObjectManager $manager,
        $maxDate
    ): void
    {
        $noteinterne = new NoteInterne();
        $random      = $faker->numberBetween(5, 50);
        $noteinterne->setTitle($faker->unique()->text($random));
        $noteinterne->setEnable($faker->numberBetween(0, 1));
        $dateDebut = $faker->unique()->dateTime($maxDate);
        $noteinterne->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $dateFin->modify('+' .$faker->numberBetween(10, 50). ' days');
        $dateFin->modify('+' .$faker->numberBetween(2, 24). ' hours');
        $noteinterne->setDateFin($dateFin);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $noteinterne->setContent(str_replace("\n\n", '<br />', $content));
        $this->addReference('noteinterne_'.$index, $noteinterne);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $noteinterne->setRefuser($user);
        $manager->persist($noteinterne);
    }

    protected function addGroupe(
        ObjectManager $manager,
        int $key,
        string $row
    ): void
    {
        $groupe = new Groupe();
        $groupe->setName($row);
        $this->addReference('groupe_'.$key, $groupe);
        $manager->persist($groupe);
    }

    protected function addEdito(
        $users,
        Generator $faker,
        int $index,
        ObjectManager $manager
    ): void
    {
        $edito  = new Edito();
        $random = $faker->numberBetween(5, 50);
        $edito->setTitle($faker->unique()->text($random));
        $enable = ($index == 0) ? true : false;
        $edito->setEnable($enable);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $edito->setContent(str_replace("\n\n", '<br />', $content));
        $this->addReference('edito_'.$index, $edito);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $manager->persist($edito);
    }

    protected function addUser(
        array $groupes,
        int $index,
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
        $this->addReference('user_'.$index, $user);
        $manager->persist($user);
        $manager->flush();
    }

    protected function addAdresse(
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
        $adresse->setPmr($faker->numberBetween(0, 1));
        $manager->persist($adresse);
    }
}
