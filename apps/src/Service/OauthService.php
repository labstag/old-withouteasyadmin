<?php

namespace Labstag\Service;

use AdamPaterson\OAuth2\Client\Provider\Slack;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\LinkedIn;
use Omines\OAuth2\Client\Provider\Gitlab;
use Rudolf\OAuth2\Client\Provider\Reddit;
use Stevenmaguire\OAuth2\Client\Provider\Bitbucket;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Vertisan\OAuth2\Client\Provider\TwitchHelix;
use Wohali\OAuth2\Client\Provider\Discord;

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
        $types = [];
        foreach (array_keys($this->configProvider) as $key) {
            $types[] = $key;
        }

        return $types;
    }

    public function getConfigProvider(): array
    {
        return $this->configProvider;
    }

    /**
     * @param mixed|null $data
     *
     * @return mixed|void
     */
    public function getIdentity($data, ?string $oauth)
    {
        if (is_null($oauth)) {
            return;
        }

        $config = $this->getConfig($oauth);
        if (!array_key_exists('identity', $config)) {
            return;
        }

        $identity = $config['identity'];
        if (!array_key_exists($identity, $data)) {
            return;
        }

        return $data[$identity];
    }

    /**
     * @return AbstractProvider|void
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

    public function ifConfigProviderEnable(string $clientName): bool
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

    public function setConfigProvider(): void
    {
        $file = __DIR__.'/../../json/oauth.json';
        $data = [];
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        $this->configProvider = $data;
    }

    protected function getConfig(string $clientName): array
    {
        if (isset($this->configProvider[$clientName])) {
            return $this->configProvider[$clientName];
        }

        return [];
    }

    protected function generateStandardProvider($clientName, $params)
    {
        $provider = null;
        $provider = $this->generateStandardProviderBitbucket($clientName, $params, $provider);
        $provider = $this->generateStandardProviderDiscord($clientName, $params, $provider);
        $provider = $this->generateStandardProviderDropbox($clientName, $params, $provider);
        $provider = $this->generateStandardProviderGithub($clientName, $params, $provider);
        $provider = $this->generateStandardProviderGitlab($clientName, $params, $provider);
        $provider = $this->generateStandardProviderGoogle($clientName, $params, $provider);
        $provider = $this->generateStandardProviderLinkedin($clientName, $params, $provider);
        $provider = $this->generateStandardProviderSlack($clientName, $params, $provider);
        $provider = $this->generateStandardProviderTwitch($clientName, $params, $provider);

        return $provider;
    }

    protected function generateStandardProviderDiscord($clientName, $params, $provider)
    {
        $provider = ($clientName == 'discord') ? new Discord($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderGithub($clientName, $params, $provider)
    {
        $provider = ($clientName == 'github') ? new Github($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderGitlab($clientName, $params, $provider)
    {
        $provider = ($clientName == 'gitlab') ? new Gitlab($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderSlack($clientName, $params, $provider)
    {
        $provider = ($clientName == 'slack') ? new Slack($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderTwitch($clientName, $params, $provider)
    {
        $provider = ($clientName == 'twitch') ? new TwitchHelix($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderGoogle($clientName, $params, $provider)
    {
        $provider = ($clientName == 'google') ? new Google($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderBitbucket($clientName, $params, $provider)
    {
        $provider = ($clientName == 'bitbucket') ? new Bitbucket($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderDropbox($clientName, $params, $provider)
    {
        $provider = ($clientName == 'dropbox') ? new Dropbox($params) : $provider;
        
        return $provider;
    }

    protected function generateStandardProviderlinkedin($clientName, $params, $provider)
    {
        $provider = ($clientName == 'linkedin') ? new LinkedIn($params) : $provider;
        
        return $provider;
    }

    protected function generateProvider($clientName, $url, $oauth)
    {
        $params = [
            'clientId'     => $oauth['id'],
            'clientSecret' => $oauth['secret'],
            'redirectUri'  => $url,
        ];
        $provider = $this->generateStandardProvider($clientName, $params);
        switch ($clientName) {
            case 'reddit':
                $provider = new Reddit(
                    [
                        'clientId'     => $oauth['id'],
                        'clientSecret' => $oauth['secret'],
                        'redirectUri'  => $url,
                        'userAgent'    => 'platform:appid:version, (by /u/username)',
                        'scopes'       => [
                            'identity',
                            'read',
                        ],
                    ]
                );
                break;
        }

        return $provider;
    }

    protected function initProvider(string $clientName): AbstractProvider
    {
        $code     = strtoupper($clientName);
        $oauth    = $this->oauthActivated[strtolower($code)];
        $url      = 'https:'.$this->router->generate(
            'connect_check',
            ['oauthCode' => $clientName],
            UrlGeneratorInterface::NETWORK_PATH
        );

        return $this->generateProvider($clientName, $url, $oauth);
    }
}
