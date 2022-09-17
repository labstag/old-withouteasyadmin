<?php

namespace Labstag\Lib;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Queue\EnqueueMethod;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\MenuRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\UserRepository;
use Labstag\Repository\WorkflowGroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Repository\WorkflowUserRepository;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\BlockService;
use Labstag\Service\DataService;
use Labstag\Service\ErrorService;
use Labstag\Service\GuardService;
use Labstag\Service\ParagraphService;
use Labstag\Service\SessionService;
use Labstag\Service\UserMailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class EventSubscriberLib implements EventSubscriberInterface
{

    // @var null|Request
    protected $request;

    public function __construct(
        protected ParagraphService $paragraphService,
        protected BlockService $blockService,
        protected ConfigurationRepository $configurationRepository,
        protected PageRepository $pageRepository,
        protected MenuRepository $menuRepository,
        protected UserRepository $userRepository,
        protected WorkflowRepository $workflowRepository,
        protected GroupeRepository $groupeRepository,
        protected WorkflowGroupeRepository $workflowGroupeRepository,
        protected WorkflowUserRepository $workflowUserRepository,
        protected Reader $reader,
        protected Environment $environment,
        protected UrlGeneratorInterface $urlGenerator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected Security $security,
        protected AttachmentRepository $attachmentRepository,
        protected ErrorService $errorService,
        protected RouterInterface $router,
        protected DataService $dataService,
        protected GuardService $guardService,
        protected TokenStorageInterface $tokenStorage,
        protected LoggerInterface $logger,
        protected ParameterBagInterface $parameterBag,
        protected EntityManagerInterface $entityManager,
        protected EnqueueMethod $enqueueMethod,
        protected CacheInterface $cache,
        protected SessionService $sessionService,
        protected RequestStack $requestStack,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected UserMailService $userMailService,
        protected EmailUserRequestHandler $emailUserRequestHandler,
        protected TranslatorInterface $translator
    )
    {
        // @var Request $request
        $this->request = $this->requestStack->getCurrentRequest();
    }
}
