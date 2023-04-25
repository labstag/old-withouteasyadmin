<?php

namespace Labstag\Service;

use Exception;
use Labstag\Entity\Configuration;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DataService
{
    protected array $config = [];

    protected array $oauthActivated = [];

    public function __construct(
        protected RepositoryService $repositoryService,
        protected CacheInterface $cache,
        protected ConfigurationRepository $configurationRepository
    ) {
        $this->setData();
    }

    public function compute(ItemInterface $item): array
    {
        $item->expiresAfter(1800);

        return $this->getConfiguration();
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getConfigWithMetatags(array $metatags): array
    {
        $config = $this->getConfig();
        foreach ($metatags as $metatag) {
            /** @var string $metatag */
            if (isset($config[$metatag])) {
                $config[$metatag] = [
                    $config[$metatag],
                ];
            }
        }

        return $config;
    }

    public function getOauthActivated(?User $user = null): array
    {
        if (is_null($user)) {
            return $this->oauthActivated;
        }

        $oauthActivateds   = $this->oauthActivated;
        $oauthConnectUsers = $user->getOauthConnectUsers();
        foreach ($oauthActivateds as $index => $oauthActivated) {
            $trouver = 0;
            foreach ($oauthConnectUsers as $oauthConnectUser) {
                /** @var OauthConnectUser $oauthConnectUser */
                if ($oauthConnectUser->getName() == $oauthActivated['type']) {
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

    protected function getConfiguration(): array
    {
        try {
            $data   = $this->configurationRepository->findAll();
            $config = [];
            /** @var Configuration $row */
            foreach ($data as $row) {
                $key          = $row->getName();
                $value        = $row->getValue();
                $config[$key] = $value;
            }
        } catch (Exception) {
            $config = [];
        }

        return $config;
    }

    protected function setOauth(array $config): void
    {
        if (!in_array('oauth', $config) || !is_iterable($config['oauth'])) {
            return;
        }

        $oauth = [];
        $data  = $config['oauth'];
        foreach ($data as $row) {
            if (1 != $row['activate']) {
                continue;
            }

            $type         = $row['type'];
            $oauth[$type] = $row;
        }

        $this->oauthActivated = $oauth;
    }

    private function setData(): void
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
}
