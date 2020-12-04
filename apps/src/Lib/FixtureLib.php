<?php

namespace Labstag\Lib;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\LienUser;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Labstag\Event\UserEntityEvent;
use Labstag\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class FixtureLib extends Fixture
{

    protected UserRepository $userRepository;

    protected EventDispatcherInterface $dispatcher;

    public function __construct(
        UserRepository $userRepository,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->dispatcher     = $dispatcher;
        $this->userRepository = $userRepository;
    }

    private function getGroupe(array $groupes, string $code): ?Groupe
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
        $old   = clone $email;
        $email->setRefuser($user);
        $email->setAdresse($faker->safeEmail);
        $user->addEmailUser($email);
        $manager->persist($user);
        $manager->persist($email);
        $manager->flush();
        $userCollectionEvent = new UserCollectionEvent();
        $userCollectionEvent->addEmailUser($old, $email);
        $this->dispatcher->dispatch($userCollectionEvent);
    }

    protected function addLink(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $lien = new LienUser();
        $old  = clone $lien;
        $lien->setRefUser($user);
        $lien->setName($faker->word());
        $lien->setAdresse($faker->url);
        $user->addLienUser($lien);
        $manager->persist($user);
        $manager->persist($lien);
        $manager->flush();
        $userCollectionEvent = new UserCollectionEvent();
        $userCollectionEvent->addLienUser($old, $lien);
        $this->dispatcher->dispatch($userCollectionEvent);
    }

    protected function addNoteInterne(
        array $users,
        Generator $faker,
        int $index,
        ObjectManager $manager,
        DateTime $maxDate
    ): void
    {
        $noteinterne = new NoteInterne();
        $random      = $faker->numberBetween(5, 50);
        $noteinterne->setTitle($faker->text($random));
        $noteinterne->setEnable((bool) $faker->numberBetween(0, 1));
        $dateDebut = $faker->dateTime($maxDate);
        $noteinterne->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $dateFin->modify('+' . $faker->numberBetween(10, 50) . ' days');
        $dateFin->modify('+' . $faker->numberBetween(2, 24) . ' hours');
        $noteinterne->setDateFin($dateFin);
        /** @var string $content */
        $content = $faker->paragraphs(4, true);
        $noteinterne->setContent(str_replace("\n\n", '<br />', $content));
        $this->addReference('noteinterne_' . $index, $noteinterne);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $noteinterne->setRefuser($user);
        $user->addNoteInterne($noteinterne);
        $manager->persist($noteinterne);
        $manager->persist($user);
        $manager->flush();
    }

    protected function addGroupe(
        ObjectManager $manager,
        int $key,
        string $row
    ): void
    {
        $groupe = new Groupe();
        $groupe->setName($row);
        $this->addReference('groupe_' . $key, $groupe);
        $manager->persist($groupe);
    }

    protected function addEdito(
        array $users,
        Generator $faker,
        int $index,
        ObjectManager $manager
    ): void
    {
        $edito  = new Edito();
        $random = $faker->numberBetween(5, 50);
        $edito->setTitle($faker->text($random));
        $enable = ($index == 0) ? true : false;
        $edito->setEnable($enable);
        /** @var string $content */
        $content = $faker->paragraphs(4, true);
        $edito->setContent(str_replace("\n\n", '<br />', $content));
        $this->addReference('edito_' . $index, $edito);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $user->addEdito($edito);
        $manager->persist($user);
        $manager->persist($edito);
        $manager->flush();
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser,
        ObjectManager $manager
    ): void
    {
        $old  = new User();
        $user = new User();
        $user->setGroupe($this->getGroupe($groupes, $dataUser['groupe']));
        $user->setEnable($dataUser['enable']);
        $user->setVerif($dataUser['verif']);
        $user->setLost($dataUser['lost']);
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $this->addReference('user_' . $index, $user);
        $manager->persist($user);
        $manager->flush();
        $this->dispatcher->dispatch(
            new UserEntityEvent($old, $user, [])
        );
    }

    protected function addPhone(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
        $number = $faker->e164PhoneNumber;
        $phone  = new PhoneUser();
        $old    = clone $phone;
        $phone->setRefuser($user);
        $phone->setNumero($number);
        $phone->setType($faker->word());
        $phone->setCountry($faker->countryCode);
        $manager->persist($phone);
        $manager->flush();
        $userCollectionEvent = new UserCollectionEvent();
        $userCollectionEvent->addPhoneUser($old, $phone);
        $this->dispatcher->dispatch($userCollectionEvent);
    }

    protected function addAdresse(
        Generator $faker,
        User $user,
        ObjectManager $manager
    ): void
    {
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
        $gps       = $latitude . ',' . $longitude;
        $adresse->setGps($gps);
        $adresse->setPmr((bool) $faker->numberBetween(0, 1));
        $user->addAdresseUser($adresse);
        $manager->persist($user);
        $manager->persist($adresse);
        $manager->flush();
        $userCollectionEvent = new UserCollectionEvent();
        $userCollectionEvent->addAdresseUser($old, $adresse);
        $this->dispatcher->dispatch($userCollectionEvent);
    }
}
