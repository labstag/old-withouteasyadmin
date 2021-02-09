<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Configuration;
use Labstag\Lib\FixtureLib;
use Symfony\Component\Dotenv\Dotenv;

class ConfigurationFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    protected function setOauth(array $env, array &$data): void
    {
        $oauth = [];
        foreach ($env as $key => $val) {
            if (0 != substr_count($key, 'OAUTH_')) {
                $code    = str_replace('OAUTH_', '', $key);
                $code    = strtolower($code);
                $explode = explode('_', $code);
                $type    = $explode[0];
                $key     = $explode[1];
                if (!isset($oauth[$type])) {
                    $activate = $this->oauthService->getActivedProvider($type);

                    $oauth[$type] = [
                        'activate' => $activate,
                        'type'     => $type,
                    ];
                }

                $oauth[$type][$key] = $val;
            }
        }

        /** @var mixed $row */
        foreach ($oauth as $row) {
            $data['oauth'][] = $row;
        }
    }

    protected function add(ObjectManager $manager): void
    {
        $data = [
            'notification'    => [
                [
                    'type'   => 'oauthconnectuser',
                    'mail'   => 1,
                    'notify' => 1,
                ],
                [
                    'type'   => 'lienuser',
                    'mail'   => 1,
                    'notify' => 1,
                ],
                [
                    'type'   => 'emailuser',
                    'mail'   => 1,
                    'notify' => 1,
                ],
                [
                    'type'   => 'phoneuser',
                    'mail'   => 1,
                    'notify' => 1,
                ],
                [
                    'type'   => 'adresseuser',
                    'mail'   => 1,
                    'notify' => 1,
                ],
            ],
            'languagedefault' => 'fr',
            'language'        => [
                'en',
                'fr',
            ],
            'site_email'      => 'contact@labstag.lxc',
            'site_no-reply'   => 'no-reply@labstag.lxc',
            'site_url'        => 'http://www.labstag.lxc',
            'site_title'      => 'labstag',
            'site_copyright'  => 'Copyright '.date('Y'),
            'oauth'           => [],
            'meta'            => [
                'viewport'    => 'width=device-width, initial-scale=1',
                'author'      => 'koromerzhin',
                'theme-color' => '#ff0000',
                'description' => '',
                'keywords'    => '',
            ],
            'disclaimer'      => [
                'activate'     => 1,
                'message'      => 'Site en construction',
                'title'        => 'Site en construction',
                'url-redirect' => 'http://www.google.fr',
            ],
            'moment'          => [
                'format' => 'MMMM Do YYYY, H:mm:ss',
                'lang'   => 'fr',
            ],
            'wysiwyg'         => ['lang' => 'fr_FR'],
            'robotstxt'       => 'User-agent: *
Allow: /',
        ];

        $dotenv = new Dotenv();
        $env    = [];
        if (is_file(__DIR__.'/../../.env')) {
            $env = $dotenv->parse(file_get_contents(__DIR__.'/../../.env'));
        }

        ksort($env);
        $this->setOauth($env, $data);

        foreach ($data as $key => $value) {
            $configuration = new Configuration();
            $configuration->setName($key);
            $configuration->setValue($value);
            $this->addReference('configuration_'.$key, $configuration);
            $manager->persist($configuration);
        }

        $manager->flush();
    }
}
