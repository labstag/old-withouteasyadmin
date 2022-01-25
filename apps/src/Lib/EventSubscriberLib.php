<?php

namespace Labstag\Lib;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\UserMailService;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\ErrorService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Labstag\Service\GuardService;
use Labstag\Service\SessionService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class EventSubscriberLib implements EventSubscriberInterface
{

    public function __construct(
        protected ErrorService $errorService,
        protected RouterInterface $router,
        protected GuardService $guardService,
        protected TokenStorageInterface $token,
        protected LoggerInterface $logger,
        protected ParameterBagInterface $containerBag,
        protected EntityManagerInterface $entityManager,
        protected EnqueueMethod $enqueue,
        protected CacheInterface $cache,
        protected SessionService $sessionService,
        protected RequestStack $requestStack,
        protected UserPasswordHasherInterface $passwordEncoder,
        protected UserMailService $userMailService,
        protected EmailUserRequestHandler $emailUserRH,
        protected TranslatorInterface $translator
    )
    {
    }
}
