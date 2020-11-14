<?php

namespace Labstag\Service;

use Labstag\Lib\GenericProviderLib;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class OauthService
{

    protected array $configProvider;

    /**
     * @var Router|RouterInterface
     */
    protected $router;

    protected DataService $dataService;

    protected array $oauthActivated;

    public function __construct(
        RouterInterface $router,
        DataService $dataService
    )
    {
        $this->dataService    = $dataService;
        $this->oauthActivated = $this->dataService->getOauthActivated();
        $this->router         = $router;
        $this->setConfigProvider();
    }

    public function getTypes(): array
    {
        $types = [
            'bitbucket',
            'amazon',
            'discord',
            'dropbox',
            'github',
            'gitlab',
            'google',
            'instagram',
            'paypal',
            'reddit',
            'twitch',
        ];

        return $types;
    }

    /**
     * @param mixed|null $data
     *
     * @return mixed|void
     */
    public function getIdentity($data, ?string $oauth)
    {
        $entity = null;
        if (is_null($oauth)) {
            return;
        }

        switch ($oauth) {
            case 'gitlab':
            case 'github':
            case 'discord':
                $this->caseDiscord($data, $entity);

                break;
            case 'google':
                $this->caseGoogle($data, $entity);

                break;
            case 'bitbucket':
                $this->caseBitbucket($data, $entity);
                break;
            default:
                break;
        }

        return $entity;
    }

    /**
     * @return GenericProviderLib|void
     */
    public function setProvider(?string $clientName)
    {
        if (is_null($clientName)) {
            return;
        }

        if ($this->ifConfigProviderEnable($clientName)) {
            return $this->initProvider($clientName);
        }
    }

    public function ifConfigProviderEnable($clientName): bool
    {
        if (!isset($this->configProvider[$clientName])) {
            return false;
        }

        if (!isset($this->oauthActivated[$clientName])) {
            return false;
        }

        return $this->oauthActivated[$clientName]['activate'];
    }

    public function getActivedProvider(?string $clientName): bool
    {
        if (is_null($clientName)) {
            return false;
        }

        return array_key_exists($clientName, $this->configProvider);
    }

    private function setConfigProviderGitlab(): array
    {
        $urlAuthorize   = 'https://gitlab.com/oauth/authorize';
        $urlAccessToken = 'https://gitlab.com/oauth/token';
        $ownerDetails   = 'https://gitlab.com/api/v4/user';

        return [
            'params'         => [
                'urlAuthorize'            => $urlAuthorize,
                'urlAccessToken'          => $urlAccessToken,
                'urlResourceOwnerDetails' => $ownerDetails,
            ],
            'redirect'       => 1,
            'scopeseparator' => ' ',
            'scopes'         => ['read_user'],
        ];
    }

    private function setConfigProviderBitbucket(): array
    {
        $urlAuthorize   = 'https://bitbucket.org/site/oauth2/authorize';
        $urlAccessToken = 'https://bitbucket.org/site/oauth2/access_token';
        $ownerDetails   = 'https://api.bitbucket.org/2.0/user';

        return [
            'params' => [
                'urlAuthorize'            => $urlAuthorize,
                'urlAccessToken'          => $urlAccessToken,
                'urlResourceOwnerDetails' => $ownerDetails,
            ],
        ];
    }

    private function setConfigProviderGithub(): array
    {
        $urlAuthorize   = 'https://github.com/login/oauth/authorize';
        $urlAccessToken = 'https://github.com/login/oauth/access_token';
        $ownerDetails   = 'https://api.github.com/user';

        return [
            'params' => [
                'urlAuthorize'            => $urlAuthorize,
                'urlAccessToken'          => $urlAccessToken,
                'urlResourceOwnerDetails' => $ownerDetails,
            ],
        ];
    }

    private function setConfigProviderDiscord(): array
    {
        $urlAuthorize   = 'https://discordapp.com/api/v6/oauth2/authorize';
        $urlAccessToken = 'https://discordapp.com/api/v6/oauth2/token';
        $ownerDetails   = 'https://discordapp.com/api/v6/users/@me';

        return [
            'params'         => [
                'urlAuthorize'            => $urlAuthorize,
                'urlAccessToken'          => $urlAccessToken,
                'urlResourceOwnerDetails' => $ownerDetails,
            ],
            'scopeseparator' => ' ',
            'scopes'         => [
                'identify',
                'email',
                'connections',
                'guilds',
                'guilds.join',
            ],
        ];
    }

    private function setConfigProviderGoogle(): array
    {
        $urlAuthorize   = 'https://accounts.google.com/o/oauth2/v2/auth';
        $urlAccessToken = 'https://www.googleapis.com/oauth2/v4/token';
        $ownerDetails   = 'https://openidconnect.googleapis.com/v1/userinfo';

        return [
            'params'         => [
                'urlAuthorize'            => $urlAuthorize,
                'urlAccessToken'          => $urlAccessToken,
                'urlResourceOwnerDetails' => $ownerDetails,
            ],
            'redirect'       => 1,
            'scopeseparator' => ' ',
            'scopes'         => [
                'openid',
                'email',
                'profile',
            ],
        ];
    }

    protected function setConfigProvider(): void
    {
        $this->configProvider = [
            'gitlab'    => $this->setConfigProviderGitlab(),
            'bitbucket' => $this->setConfigProviderBitbucket(),
            'github'    => $this->setConfigProviderGithub(),
            'discord'   => $this->setConfigProviderDiscord(),
            'google'    => $this->setConfigProviderGoogle(),
        ];
    }

    private function getConfig($clientName)
    {
        if (isset($this->configProvider[$clientName])) {
            return $this->configProvider[$clientName];
        }

        return [];
    }

    protected function initProvider(string $clientName): GenericProviderLib
    {
        $config = $this->getConfig($clientName);
        if (isset($config['redirect'])) {
            $config['params']['redirectUri'] = $this->router->generate(
                'connect_check',
                ['oauthCode' => $clientName],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $code  = strtoupper($clientName);
        $oauth = $this->oauthActivated[strtolower($code)];

        $config['params']['clientId']     = $oauth['id'];
        $config['params']['clientSecret'] = $oauth['secret'];

        $provider = new GenericProviderLib(
            $config['params']
        );
        if (isset($config['scopes'])) {
            $provider->setDefaultScopes($config['scopes']);
        }

        if (isset($config['scopeseparator'])) {
            $provider->setScopeSeparator($config['scopeseparator']);
        }

        return $provider;
    }

    /**
     * @param mixed $entity
     */
    private function caseBitbucket(array $data, &$entity): void
    {
        if (!isset($data['uuid'])) {
            return;
        }

        $entity = $data['uuid'];
    }

    /**
     * @param mixed $entity
     */
    private function caseGoogle(array $data, &$entity): void
    {
        if (!isset($data['sub'])) {
            return;
        }

        $entity = $data['sub'];
    }

    /**
     * @param mixed $entity
     */
    private function caseDiscord(array $data, &$entity): void
    {
        if (!isset($data['id'])) {
            return;
        }

        $entity = $data['id'];
    }
}
