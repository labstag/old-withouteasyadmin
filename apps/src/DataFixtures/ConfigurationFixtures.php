<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Configuration;
use Labstag\Lib\FixtureLib;

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
        $data = $this->installService->getData('config');
        $env  = $this->installService->getEnv();
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
