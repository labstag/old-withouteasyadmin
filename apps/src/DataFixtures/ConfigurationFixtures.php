<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Configuration;
use Labstag\Lib\FixtureLib;
use Symfony\Component\Dotenv\Dotenv;
use Labstag\Service\OauthService;


/**
 * @codeCoverageIgnore
 */
class ConfigurationFixtures extends FixtureLib
{

    private OauthService $oauthService;

    public function __construct(OauthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    private function setGeonames($env, &$data)
    {
        $geonames = [];
        if (array_key_exists('GEONAMES', $env)) {
            $explode = explode(',', (string) $env['GEONAMES']);
            foreach ($explode as $code) {
                $geonames[] = [
                    'name'     => $code,
                    'activate' => false,
                ];
            }
        }

        $data['geonames'] = $geonames;
    }

    private function setOauth($env, &$data)
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

    private function add(ObjectManager $manager): void
    {
        $viewport = 'width=device-width, initial-scale=1, shrink-to-fit=no';
        $data     = [
            'languagedefault' => 'fr',
            'language'        => [
                'en',
                'fr',
            ],
            'site_email'      => 'contact@letoullec.fr',
            'site_no-reply'   => 'no-reply@labstag.fr',
            'site_title'      => 'labstag',
            'site_copyright'  => 'Copyright '.date('Y'),
            'oauth'           => [],
            'meta'            => [
                [
                    'viewport'    => $viewport,
                    'author'      => 'koromerzhin',
                    'theme-color' => '#ff0000',
                    'description' => '',
                    'keywords'    => '',
                ],
            ],
            'disclaimer'      => [
                [
                    'activate'     => 0,
                    'message'      => '',
                    'title'        => '',
                    'url-redirect' => 'http://www.google.fr',
                ],
            ],
            'moment'          => [
                [
                    'format' => 'MMMM Do YYYY, H:mm:ss',
                    'lang'   => 'fr',
                ],
            ],
            'wysiwyg'         => [
                ['lang' => 'fr_FR'],
            ],
            'robotstxt'       => 'User-agent: *
Allow: /',
            'datatable'       => [
                [
                    'lang'     => 'fr-FR',
                    'pagelist' => '[5, 10, 25, 50, All]',
                ],
            ],
        ];

        $dotenv = new Dotenv();
        $env    = $dotenv->parse(file_get_contents(__DIR__.'/../../.env'));

        ksort($env);
        $this->setGeonames($env, $data);
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
