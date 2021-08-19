<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Entity\Configuration;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Repository\ConfigurationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigurationEntitySubscriber implements EventSubscriberInterface
{

    protected CacheInterface $cache;

    protected ContainerBagInterface $containerBag;

    protected EntityManagerInterface $entityManager;

    protected FlashBagInterface $flashbag;

    protected LoggerInterface $logger;

    protected ConfigurationRepository $repository;

    protected SessionInterface $session;

    protected RequestStack $requestStack;

    public function __construct(
        FlashBagInterface $flashbag,
        LoggerInterface $logger,
        ContainerBagInterface $containerBag,
        EntityManagerInterface $entityManager,
        ConfigurationRepository $repository,
        CacheInterface $cache,
        RequestStack $requestStack
    )
    {
        $this->flashbag      = $flashbag;
        $this->containerBag  = $containerBag;
        $this->cache         = $cache;
        $this->entityManager = $entityManager;
        $this->repository    = $repository;
        $this->logger        = $logger;
        $this->requestStack  = $requestStack;
        $this->session       = $requestStack->getSession();
    }

    public static function getSubscribedEvents()
    {
        return [ConfigurationEntityEvent::class => 'onEvent'];
    }

    public function onEvent(ConfigurationEntityEvent $event): void
    {
        $this->cache->delete('configuration');
        $post = $event->getPost();
        $this->setRobotsTxt($post);
        $this->flushPostConfiguration($post);
    }

    protected function flushPostConfiguration(array $post): void
    {
        foreach ($post as $key => $value) {
            if ('_token' == $key) {
                continue;
            }

            $configuration = $this->repository->findOneBy(['name' => $key]);
            if (!$configuration instanceof Configuration) {
                $configuration = new Configuration();
                $configuration->setName($key);
            }

            if (in_array($key, $this->getParameter('metatags'))) {
                $value = $value[0];
            }

            $configuration->setValue($value);
            $this->entityManager->persist($configuration);
        }

        $this->entityManager->flush();
        $this->flashbag->add('success', 'Données sauvegardé');
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    protected function setRobotsTxt(array $post): void
    {
        if (!isset($post['robotstxt'])) {
            return;
        }

        try {
            $value = $post['robotstxt'];
            $file  = 'robots.txt';
            if (is_file($file)) {
                unlink($file);
            }

            file_put_contents($file, $value);
            $msg = sprintf(
                'fichier %s modifié',
                $file
            );
            $this->logger->info($msg);
            $this->flashbag->add('success', $msg);
        } catch (Exception $exception) {
            $errorMsg = sprintf(
                'Exception : Erreur %s dans %s L.%s : %s',
                $exception->getCode(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getMessage()
            );
            $this->logger->error($errorMsg);
            $this->flashbag->add('danger', $errorMsg);
        }
    }
}
