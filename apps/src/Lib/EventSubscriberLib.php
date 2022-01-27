<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Queue\EnqueueMethod;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\ErrorService;
use Labstag\Service\GuardService;
use Labstag\Service\SessionService;
use Labstag\Service\UserMailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
