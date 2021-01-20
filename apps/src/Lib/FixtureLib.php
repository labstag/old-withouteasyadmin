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
use Labstag\Service\OauthService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

abstract class FixtureLib extends Fixture
{

    protected UserRepository $userRepository;

    protected EventDispatcherInterface $dispatcher;
    
    protected OauthService $oauthService;
    
    protected Environment $twig;

    protected GroupeRepository $groupeRepository;

    protected EmailUserRequestHandler $emailUserRequestHandler;

    protected LienUserRequestHandler $lienUserRequestHandler;
    
    protected NoteInterneRequestHandler $noteInterneRequestHandler;

    protected GroupeRequestHandler $groupeRequestHandler;

    protected EditoRequestHandler $editoRequestHandler;

    protected PhoneUserRequestHandler $phoneUserRequestHandler;

    protected AdresseUserRequestHandler $adresseUserRequestHandler;

    protected TemplateRequestHandler $templateRequestHandler;

    public function __construct(
        OauthService $oauthService,
        UserRepository $userRepository,
        GroupeRepository $groupeRepository,
        EventDispatcherInterface $dispatcher,
        Environment $twig,
        EmailUserRequestHandler $emailUserRequestHandler,
        LienUserRequestHandler $lienUserRequestHandler,
        NoteInterneRequestHandler $noteInterneRequestHandler,
        GroupeRequestHandler $groupeRequestHandler,
        EditoRequestHandler $editoRequestHandler,
        UserRequestHandler $userRequestHandler,
        PhoneUserRequestHandler $phoneUserRequestHandler,
        AdresseUserRequestHandler $adresseUserRequestHandler,
        TemplateRequestHandler $templateRequestHandler
    )
    {
        $this->twig = $twig;
        $this->dispatcher     = $dispatcher;
        $this->userRepository = $userRepository;
        $this->oauthService = $oauthService;
        $this->groupeRepository = $groupeRepository;
        $this->templateRequestHandler = $templateRequestHandler;
        $this->adresseUserRequestHandler = $adresseUserRequestHandler;
        $this->phoneUserRequestHandler = $phoneUserRequestHandler;
        $this->userRequestHandler = $userRequestHandler;
        $this->editoRequestHandler = $editoRequestHandler;
        $this->groupeRequestHandler = $groupeRequestHandler;
        $this->noteInterneRequestHandler = $noteInterneRequestHandler;
        $this->lienUserRequestHandler = $lienUserRequestHandler;
        $this->emailUserRequestHandler = $emailUserRequestHandler;
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
        $this->emailUserRequestHandler->create($old, $email);
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
        $this->lienUserRequestHandler->create($old, $lien);
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
        $old = clone $noteinterne;
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
        $this->noteInterneRequestHandler->create($old, $noteinterne);
    }

    protected function addGroupe(
        ObjectManager $manager,
        int $key,
        string $row
    ): void
    {
        $groupe = new Groupe();
        $old = clone $groupe;
        $groupe->setName($row);
        $this->addReference('groupe_' . $key, $groupe);
        $this->groupeRequestHandler->create($old, $groupe);
    }

    protected function addEdito(
        array $users,
        Generator $faker,
        int $index,
        ObjectManager $manager
    ): void
    {
        $edito = new Edito();
        $old = clone $edito;
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
        $this->editoRequestHandler->create($old, $edito);
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser,
        ObjectManager $manager
    ): void
    {
        $user = new User();
        $old  = clone $user;;
        $user->setGroupe($this->getGroupe($groupes, $dataUser['groupe']));
        $user->setEnable($dataUser['enable']);
        $user->setVerif($dataUser['verif']);
        $user->setLost($dataUser['lost']);
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $this->addReference('user_' . $index, $user);
        $this->userRequestHandler->create($old, $user);
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
        $this->phoneUserRequestHandler->create($old, $phone);
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
        $this->adresseUserRequestHandler->create($old, $adresse);
    }
}
