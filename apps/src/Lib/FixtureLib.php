<?php

namespace Labstag\Lib;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Labstag\DataFixtures\CategoryFixtures;
use Labstag\DataFixtures\DataFixtures;
use Labstag\DataFixtures\LibelleFixtures;
use Labstag\DataFixtures\UserFixtures;
use Labstag\Entity\Groupe;
use Labstag\Entity\Meta;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\BlockRequestHandler;
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
use Labstag\RequestHandler\MenuRequestHandler;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\RequestHandler\RenderRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\BlockService;
use Labstag\Service\ErrorService;
use Labstag\Service\FileService;
use Labstag\Service\GuardService;
use Labstag\Service\InstallService;
use Labstag\Service\ParagraphService;
use Labstag\Service\UserService;
use Mmo\Faker\LoremSpaceProvider;
use Mmo\Faker\PicsumProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

abstract class FixtureLib extends Fixture
{
    /**
     * @var int
     */
    protected const NUMBER_ADRESSE = 25;

    /**
     * @var int
     */
    protected const NUMBER_BOOKMARK = 25;

    /**
     * @var int
     */
    protected const NUMBER_CATEGORY = 50;

    /**
     * @var int
     */
    protected const NUMBER_CHAPTER = 25;

    /**
     * @var int
     */
    protected const NUMBER_EDITO = 25;

    /**
     * @var int
     */
    protected const NUMBER_EMAIL = 25;

    /**
     * @var int
     */
    protected const NUMBER_HISTORY = 25;

    /**
     * @var int
     */
    protected const NUMBER_LIBELLE = 10;

    /**
     * @var int
     */
    protected const NUMBER_LINK = 25;

    /**
     * @var int
     */
    protected const NUMBER_NOTEINTERNE = 25;

    /**
     * @var int
     */
    protected const NUMBER_PHONE = 25;

    /**
     * @var int
     */
    protected const NUMBER_POST = 100;

    /**
     * @var int
     */
    protected const NUMBER_TEMPLATES = 10;

    public function __construct(
        protected FileService $fileService,
        protected UserService $userService,
        protected ErrorService $errorService,
        protected ParagraphService $paragraphService,
        protected LoggerInterface $logger,
        protected ContainerBagInterface $containerBag,
        protected UploadAnnotationReader $uploadAnnotationReader,
        protected InstallService $installService,
        protected UserRepository $userRepository,
        protected GroupeRepository $groupeRepository,
        protected GuardService $guardService,
        protected Environment $twigEnvironment,
        protected BlockService $blockService,
        protected EmailUserRequestHandler $emailUserRequestHandler,
        protected LinkUserRequestHandler $linkUserRequestHandler,
        protected MemoRequestHandler $memoRequestHandler,
        protected GroupeRequestHandler $groupeRequestHandler,
        protected EditoRequestHandler $editoRequestHandler,
        protected UserRequestHandler $userRequestHandler,
        protected PhoneUserRequestHandler $phoneUserRequestHandler,
        protected AttachmentRequestHandler $attachmentRequestHandler,
        protected AddressUserRequestHandler $addressUserRequestHandler,
        protected TemplateRequestHandler $templateRequestHandler,
        protected LibelleRequestHandler $libelleRequestHandler,
        protected CacheInterface $cache,
        protected BookmarkRequestHandler $bookmarkRequestHandler,
        protected PostRequestHandler $postRequestHandler,
        protected CategoryRequestHandler $categoryRequestHandler,
        protected HistoryRequestHandler $historyRequestHandler,
        protected ChapterRequestHandler $chapterRequestHandler,
        protected ParagraphRequestHandler $paragraphRequestHandler,
        protected BlockRequestHandler $blockRequestHandler,
        protected LayoutRequestHandler $layoutRequestHandler,
        protected MenuRequestHandler $menuRequestHandler,
        protected PageRequestHandler $pageRequestHandler,
        protected RenderRequestHandler $renderRequestHandler
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

    protected function addParagraphs($entity, $paragraphs)
    {
        foreach ($paragraphs as $paragraph) {
            $this->paragraphService->add($entity, $paragraph);
        }
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

    protected function loadForeach($number, $method)
    {
        $faker = $this->setFaker();
        $statesTab = $this->getStatesData();
        for ($index = 0; $index < $number; ++$index) {
            $stateId = array_rand($statesTab);
            $states = $statesTab[$stateId];
            call_user_func([$this, $method], $faker, $index, $states);
        }
    }

    protected function loadForeachUser($number, $method)
    {
        $faker = $this->setFaker();
        $users = $this->installService->getData('user');
        for ($index = 0; $index < $number; ++$index) {
            $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
            $user = $this->getReference('user_'.$indexUser);
            call_user_func([$this, $method], $faker, $user);
        }
    }

    protected function setFaker()
    {
        $generator = Factory::create('fr_FR');
        $generator->addProvider(new PicsumProvider($generator));
        $generator->addProvider(new LoremSpaceProvider($generator));

        return $generator;
    }

    protected function setLibelles($faker, $entity): void
    {
        if (1 != random_int(0, 1)) {
            return;
        }

        $nbr = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
        for ($i = 0; $i < $nbr; ++$i) {
            $indexLibelle = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
            $libelle = $this->getReference('libelle_'.$indexLibelle);
            $entity->addLibelle($libelle);
        }
    }

    protected function setMeta(Meta $meta)
    {
        $faker = $this->setFaker();
        $meta->setTitle($faker->unique()->colorName());
        $meta->setDescription($faker->unique()->sentence());
        $meta->setKeywords($faker->unique()->words(random_int(1, 5), true));
    }

    protected function upload($entity, Generator $generator): void
    {
        // @var resource $finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
        $asciiSlugger = new AsciiSlugger();
        foreach ($annotations as $annotation) {
            $path = $this->getParameter('file_directory').'/'.$annotation->getPath();
            $accessor = PropertyAccess::createPropertyAccessor();
            $title = $accessor->getValue($entity, $annotation->getSlug());
            $slug = $asciiSlugger->slug($title);

            try {
                /** @var PicsumProvider $generator */
                $image = $generator->picsum(null, 640, 480, true);
                $content = file_get_contents($image);
                // @var resource $tmpfile
                $tmpfile = tmpfile();
                $data = stream_get_meta_data($tmpfile);
                file_put_contents($data['uri'], $content);
                $file = new UploadedFile(
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

            if (isset($file)) {
                $attachment = $this->fileService->setAttachment($file);
                $accessor->setValue($entity, $annotation->getFilename(), $attachment);
            }
        }
    }
}
