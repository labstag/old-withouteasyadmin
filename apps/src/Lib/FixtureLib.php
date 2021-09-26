<?php

namespace Labstag\Lib;

use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\AdresseUser;
use Labstag\Entity\Attachment;
use Labstag\Entity\EmailUser;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AdresseUserRequestHandler;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\RequestHandler\LienUserRequestHandler;
use Labstag\RequestHandler\NoteInterneRequestHandler;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\GuardService;
use Labstag\Service\InstallService;
use Labstag\Service\OauthService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

abstract class FixtureLib extends Fixture
{
    protected const NUMBER_ADRESSE = 25;

    protected const NUMBER_CATEGORY = 50;

    protected const NUMBER_EDITO = 25;

    protected const NUMBER_EMAIL = 25;

    protected const NUMBER_LIBELLE = 10;

    protected const NUMBER_LIEN = 25;

    protected const NUMBER_NOTEINTERNE = 25;

    protected const NUMBER_PHONE = 25;

    protected const NUMBER_POST = 100;

    protected const NUMBER_TEMPLATES = 10;

    protected AdresseUserRequestHandler $adresseUserRH;

    protected AttachmentRequestHandler $attachmentRH;

    protected CacheInterface $cache;

    protected CategoryRequestHandler $categoryRH;

    protected ContainerBagInterface $containerBag;

    protected EditoRequestHandler $editoRH;

    protected EmailUserRequestHandler $emailUserRH;

    protected GroupeRepository $groupeRepository;

    protected GroupeRequestHandler $groupeRH;

    protected GuardService $guardService;

    protected InstallService $installService;

    protected LibelleRequestHandler $libelleRH;

    protected LienUserRequestHandler $lienUserRH;

    protected LoggerInterface $logger;

    protected NoteInterneRequestHandler $noteInterneRH;

    protected OauthService $oauthService;

    protected PhoneUserRequestHandler $phoneUserRH;

    protected TemplateRequestHandler $templateRH;

    protected Environment $twig;

    protected UploadAnnotationReader $uploadAnnotReader;

    protected UserRepository $userRepository;

    protected UserRequestHandler $userRH;

    public function __construct(
        LoggerInterface $logger,
        ContainerBagInterface $containerBag,
        UploadAnnotationReader $uploadAnnotReader,
        InstallService $installService,
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
        AttachmentRequestHandler $attachmentRH,
        AdresseUserRequestHandler $adresseUserRH,
        TemplateRequestHandler $templateRH,
        LibelleRequestHandler $libelleRH,
        CacheInterface $cache,
        CategoryRequestHandler $categoryRH,
    )
    {
        $this->categoryRH        = $categoryRH;
        $this->attachmentRH      = $attachmentRH;
        $this->logger            = $logger;
        $this->containerBag      = $containerBag;
        $this->libelleRH         = $libelleRH;
        $this->uploadAnnotReader = $uploadAnnotReader;
        $this->installService    = $installService;
        $this->cache             = $cache;
        $this->guardService      = $guardService;
        $this->twig              = $twig;
        $this->userRepository    = $userRepository;
        $this->oauthService      = $oauthService;
        $this->groupeRepository  = $groupeRepository;
        $this->templateRH        = $templateRH;
        $this->adresseUserRH     = $adresseUserRH;
        $this->phoneUserRH       = $phoneUserRH;
        $this->userRH            = $userRH;
        $this->editoRH           = $editoRH;
        $this->groupeRH          = $groupeRH;
        $this->noteInterneRH     = $noteInterneRH;
        $this->lienUserRH        = $lienUserRH;
        $this->emailUserRH       = $emailUserRH;
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
        $gps       = $latitude.','.$longitude;
        $adresse->setGps($gps);
        $adresse->setPmr((bool) rand(0, 1));
        $this->adresseUserRH->handle($old, $adresse);
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

    protected function addGroupe(
        int $key,
        string $row
    ): void
    {
        $groupe = new Groupe();
        $old    = clone $groupe;
        $groupe->setCode($row);
        $groupe->setName($row);
        $this->addReference('groupe_'.$key, $groupe);
        $this->groupeRH->handle($old, $groupe);
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    protected function getRefgroupe(array $groupes, string $code): ?Groupe
    {
        foreach ($groupes as $groupe) {
            if ($groupe->getCode() == $code) {
                return $groupe;
            }
        }

        return null;
    }

    protected function setFaker()
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        return $faker;
    }

    protected function upload($entity, Generator $faker)
    {
        if (!$this->uploadAnnotReader->isUploadable($entity)) {
            return;
        }

        // @var resource $finfo
        $finfo       = finfo_open(FILEINFO_MIME_TYPE);
        $annotations = $this->uploadAnnotReader->getUploadableFields($entity);
        $slugger     = new AsciiSlugger();
        foreach ($annotations as $annotation) {
            $path       = $this->getParameter('file_directory').'/'.$annotation->getPath();
            $accessor   = PropertyAccess::createPropertyAccessor();
            $title      = $accessor->getValue($entity, $annotation->getSlug());
            $slug       = $slugger->slug($title);
            $attachment = new Attachment();
            $old        = clone $attachment;

            try {
                $image   = $faker->imageUrl(
                    1920,
                    1920,
                    true
                );
                $content = file_get_contents($image);
                // @var resource $tmpfile
                $tmpfile = tmpfile();
                $data    = stream_get_meta_data($tmpfile);
                file_put_contents($data['uri'], $content);
                $file     = new UploadedFile(
                    $data['uri'],
                    $slug.'.jpg',
                    (string) finfo_file($finfo, $data['uri']),
                    null,
                    true
                );
                $filename = $file->getClientOriginalName();
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move(
                    $path,
                    $filename
                );
                $file = $path.'/'.$filename;
            } catch (Exception $exception) {
                $errorMsg = sprintf(
                    'Exception : Erreur %s dans %s L.%s : %s',
                    $exception->getCode(),
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getMessage()
                );
                $this->logger->error($errorMsg);
                echo $exception->getMessage();
            }

            $file = $path.'/'.$filename;
            $attachment->setMimeType(mime_content_type($file));
            $attachment->setSize(filesize($file));
            $size = getimagesize($file);
            $attachment->setDimensions(is_array($size) ? $size : []);
            $attachment->setName(
                str_replace(
                    $this->getParameter('kernel.project_dir').'/public/',
                    '',
                    $file
                )
            );
            $this->attachmentRH->handle($old, $attachment);
            $accessor->setValue($entity, $annotation->getFilename(), $attachment);
        }
    }
}
