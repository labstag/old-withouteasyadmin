<?php

namespace Labstag\Service;

use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DataService
{

    protected array $oauthActivated = [];

    protected array $config = [];

    protected ConfigurationRepository $repository;

    protected CacheInterface $cache;

    public function __construct(
        ConfigurationRepository $repository,
        CacheInterface $cache
    )
    {
        $this->cache      = $cache;
        $this->repository = $repository;
        $this->setData();
    }

    public function getOauthActivated(): array
    {
        return $this->oauthActivated;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    protected function setData(): void
    {
        $config = $this->cache->get(
            'configuration',
            [
                $this,
                'compute',
            ]
        );
        if (0 === count($config)) {
            $config = $this->getConfiguration();
        }

        $this->config = $config;

        $this->setOauth($config);
    }

    public function compute(ItemInterface $item): array
    {
        $item->expiresAfter(1800);

        return $this->getConfiguration();
    }

    protected function getConfiguration()
    {
        $data   = $this->repository->findAll();
        $config = [];
        /** @var Configuration $row */
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        return $config;
    }

    protected function setOauth(array $config): void
    {
        if (!isset($config['oauth']) || !is_array($config['oauth'])) {
            return;
        }

        $oauth = [];
        $data  = $config['oauth'];
        foreach ($data as $row) {
            if (1 == $row['activate']) {
                $type         = $row['type'];
                $oauth[$type] = $row;
            }
        }

        $this->oauthActivated = $oauth;
    }
}
