<?php

namespace Labstag\Lib;

use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Labstag\DataFixtures\CategoryFixtures;
use Labstag\DataFixtures\DataFixtures;
use Labstag\DataFixtures\LibelleFixtures;
use Labstag\DataFixtures\UserFixtures;
use Labstag\Entity\Attachment;
use Labstag\Entity\Groupe;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\RequestHandler\LayoutRequestHandler;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\RequestHandler\LinkUserRequestHandler;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\ErrorService;
use Labstag\Service\GuardService;
use Labstag\Service\InstallService;
use Labstag\Service\UserService;
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

    protected const NUMBER_BOOKMARK = 25;

    protected const NUMBER_CATEGORY = 50;

    protected const NUMBER_CHAPTER = 25;

    protected const NUMBER_EDITO = 25;

    protected const NUMBER_EMAIL = 25;

    protected const NUMBER_HISTORY = 25;

    protected const NUMBER_LIBELLE = 10;

    protected const NUMBER_LINK = 25;

    protected const NUMBER_NOTEINTERNE = 25;

    protected const NUMBER_PHONE = 25;

    protected const NUMBER_POST = 100;

    protected const NUMBER_TEMPLATES = 10;

    public function __construct(
        protected UserService $userService,
        protected ErrorService $errorService,
        protected LoggerInterface $logger,
        protected ContainerBagInterface $containerBag,
        protected UploadAnnotationReader $uploadAnnotReader,
        protected InstallService $installService,
        protected UserRepository $userRepository,
        protected GroupeRepository $groupeRepository,
        protected GuardService $guardService,
        protected Environment $twig,
        protected EmailUserRequestHandler $emailUserRH,
        protected LinkUserRequestHandler $linkUserRH,
        protected MemoRequestHandler $noteInterneRH,
        protected GroupeRequestHandler $groupeRH,
        protected LayoutRequestHandler $layoutRH,
        protected EditoRequestHandler $editoRH,
        protected UserRequestHandler $userRH,
        protected PhoneUserRequestHandler $phoneUserRH,
        protected AttachmentRequestHandler $attachmentRH,
        protected AddressUserRequestHandler $addressUserRH,
        protected TemplateRequestHandler $templateRH,
        protected LibelleRequestHandler $libelleRH,
        protected CacheInterface $cache,
        protected BookmarkRequestHandler $bookmarkRH,
        protected PostRequestHandler $postRH,
        protected CategoryRequestHandler $categoryRH,
        protected HistoryRequestHandler $historyRH,
        protected ChapterRequestHandler $chapterRH
    )
    {
    }

    public function getDependenciesBookmarkPost()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
            LibelleFixtures::class,
            CategoryFixtures::class,
        ];
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

    protected function getStatesData()
    {
        return [
            ['submit'],
            [
                'submit',
                'relire',
            ],
            [
                'submit',
                'relire',
                'corriger',
            ],
            [
                'submit',
                'relire',
                'publier',
            ],
            [
                'submit',
                'relire',
                'rejeter',
            ],
        ];
    }

    protected function loadForeachUser($number, $method)
    {
        $faker = $this->setFaker();
        $users = $this->installService->getData('user');
        for ($index = 0; $index < $number; ++$index) {
            $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
            $user      = $this->getReference('user_'.$indexUser);
            $this->{$method}($faker, $user);
        }
    }

    protected function loadForeach($number, $method)
    {
        $faker     = $this->setFaker();
        $statesTab = $this->getStatesData();
        for ($index = 0; $index < $number; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->$method($faker, $index, $states);
        }
    }

    protected function setFaker()
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        return $faker;
    }

    protected function setLibelles($faker, $entity)
    {
        if (1 != random_int(0, 1)) {
            return;
        }

        $nbr = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
        for ($i = 0; $i < $nbr; ++$i) {
            $indexLibelle = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
            $libelle      = $this->getReference('libelle_'.$indexLibelle);
            $entity->addLibelle($libelle);
        }
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
                $image   = $faker->imageUrl(1920, 1920);
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
                $this->errorService->set($exception);
                echo $exception->getMessage();
            }

            if (isset($filename)) {
                $file = $path.'/'.$filename;
                $attachment->setMimeType(mime_content_type($file));
                $attachment->setSize(filesize($file));
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
}
