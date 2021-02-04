<?php

namespace Labstag\Lib;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\LienUser;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AdresseUserRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\RequestHandler\LienUserRequestHandler;
use Labstag\RequestHandler\NoteInterneRequestHandler;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\GuardService;
use Labstag\Service\OauthService;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

abstract class FixtureLib extends Fixture
{

    protected UserRepository $userRepository;

    protected OauthService $oauthService;

    protected Environment $twig;

    protected GroupeRepository $groupeRepository;

    protected EmailUserRequestHandler $emailUserRH;

    protected LienUserRequestHandler $lienUserRH;

    protected NoteInterneRequestHandler $noteInterneRH;

    protected GroupeRequestHandler $groupeRH;

    protected EditoRequestHandler $editoRH;

    protected PhoneUserRequestHandler $phoneUserRH;

    protected AdresseUserRequestHandler $adresseUserRH;

    protected TemplateRequestHandler $templateRH;

    protected UserRequestHandler $userRH;

    protected GuardService $guardService;

    protected CacheInterface $cache;

    public function __construct(
        OauthService $oauthService,
        UserRepository $userRepository,
        GroupeRepository $groupeRepository,
        GuardService $guardService,
        Environment $twig,
        EmailUserRequestHandler $emailUserRH,
        LienUserRequestHandler $lienUserRH,
        NoteInterneRequestHandler $noteInterneRH,
        GroupeRequestHandler $groupeRH,
        EditoRequestHandler $editoRH,
        UserRequestHandler $userRH,
        PhoneUserRequestHandler $phoneUserRH,
        AdresseUserRequestHandler $adresseUserRH,
        TemplateRequestHandler $templateRH,
        CacheInterface $cache
    )
    {
        $this->cache            = $cache;
        $this->guardService     = $guardService;
        $this->twig             = $twig;
        $this->userRepository   = $userRepository;
        $this->oauthService     = $oauthService;
        $this->groupeRepository = $groupeRepository;
        $this->templateRH       = $templateRH;
        $this->adresseUserRH    = $adresseUserRH;
        $this->phoneUserRH      = $phoneUserRH;
        $this->userRH           = $userRH;
        $this->editoRH          = $editoRH;
        $this->groupeRH         = $groupeRH;
        $this->noteInterneRH    = $noteInterneRH;
        $this->lienUserRH       = $lienUserRH;
        $this->emailUserRH      = $emailUserRH;
    }

    protected function getGroupe(array $groupes, string $code): ?Groupe
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
        User $user
    ): void
    {
        $email = new EmailUser();
        $old   = clone $email;
        $email->setRefuser($user);
        $email->setAdresse($faker->safeEmail);
        $this->emailUserRH->handle($old, $email);
    }

    protected function addLink(
        Generator $faker,
        User $user
    ): void
    {
        $lien = new LienUser();
        $old  = clone $lien;
        $lien->setRefUser($user);
        $lien->setName($faker->word());
        $lien->setAdresse($faker->url);
        $this->lienUserRH->handle($old, $lien);
    }

    protected function addNoteInterne(
        array $users,
        Generator $faker,
        int $index,
        DateTime $maxDate,
        array $states
    ): void
    {
        $noteinterne = new NoteInterne();
        $old         = clone $noteinterne;
        $random      = $faker->numberBetween(5, 50);
        $noteinterne->setTitle($faker->text($random));
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
        $this->noteInterneRH->handle($old, $noteinterne);
        $this->noteInterneRH->changeWorkflowState($noteinterne, $states);
    }

    protected function addGroupe(
        int $key,
        string $row
    ): void
    {
        $groupe = new Groupe();
        $old    = clone $groupe;
        $groupe->setName($row);
        $this->addReference('groupe_' . $key, $groupe);
        $this->groupeRH->handle($old, $groupe);
    }

    protected function addEdito(
        array $users,
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $edito  = new Edito();
        $old    = clone $edito;
        $random = $faker->numberBetween(5, 50);
        $edito->setTitle($faker->text($random));
        /** @var string $content */
        $content = $faker->paragraphs(4, true);
        $edito->setContent(str_replace("\n\n", '<br />', $content));
        $this->addReference('edito_' . $index, $edito);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $this->editoRH->handle($old, $edito);
        $this->editoRH->changeWorkflowState($edito, $states);
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser
    ): void
    {
        $user = new User();
        $old  = clone $user;

        $user->setGroupe($this->getGroupe($groupes, $dataUser['groupe']));
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $this->addReference('user_' . $index, $user);
        $this->userRH->handle($old, $user);
        $this->userRH->changeWorkflowState($user, $dataUser['state']);
    }

    protected function addPhone(
        Generator $faker,
        User $user,
        array $states
    ): void
    {
        $number = $faker->e164PhoneNumber;
        $phone  = new PhoneUser();
        $old    = clone $phone;
        $phone->setRefuser($user);
        $phone->setNumero($number);
        $phone->setType($faker->word());
        $phone->setCountry($faker->countryCode);
        $this->phoneUserRH->handle($old, $phone);
        $this->phoneUserRH->changeWorkflowState($phone, $states);
    }

    protected function addAdresse(
        Generator $faker,
        User $user
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
        $this->adresseUserRH->handle($old, $adresse);
    }
}
