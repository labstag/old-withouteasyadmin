<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Configuration;
use Labstag\Entity\User;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DataService
{

    protected array $config = [];

    protected array $oauthActivated = [];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected CacheInterface $cache,
        protected ConfigurationRepository $configurationRepository
    )
    {
        $this->setData();
    }

    /**
     * @return array<int|string, mixed>
     */
    public function compute(ItemInterface $item): array
    {
        $item->expiresAfter(1800);

        return $this->getConfiguration();
    }

    /**
     * @return mixed[]
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return mixed[]
     */
    public function getOauthActivated(?User $user = null): array
    {
        if (is_null($user)) {
            return $this->oauthActivated;
        }

        $oauthActivateds = $this->oauthActivated;
        $oauthUsers = $user->getOauthConnectUsers();
        foreach ($oauthActivateds as $index => $oauthActivated) {
            $trouver = 0;
            foreach ($oauthUsers as $oauthUser) {
                if ($oauthUser->getName() == $oauthActivated['type']) {
                    $trouver = 1;

                    break;
                }
            }

            if (1 === $trouver) {
                unset($oauthActivateds[$index]);
            }
        }

        return $oauthActivateds;
    }

    /**
     * @return array<int|string, mixed>
     */
    protected function getConfiguration(): array
    {
        $data = $this->configurationRepository->findAll();
        $config = [];
        // @var Configuration $row
        foreach ($data as $row) {
            $key = $row->getName();
            $value = $row->getValue();
            $config[$key] = $value;
        }

        return $config;
    }

    protected function setData(): void
    {
        $config = $this->cache->get(
            'configuration',
            $this->compute(...)
        );
        if (0 === (is_countable($config) ? count($config) : 0)) {
            $config = $this->getConfiguration();
        }

        $this->config = $config;

        $this->setOauth($config);
    }

    protected function setOauth(array $config): void
    {
        if (!isset($config['oauth']) || !is_array($config['oauth'])) {
            return;
        }

        $oauth = [];
        $data = $config['oauth'];
        foreach ($data as $row) {
            if (1 != $row['activate']) {
                continue;
            }

            $type = $row['type'];
            $oauth[$type] = $row;
        }

        $this->oauthActivated = $oauth;
    }
}
