<?php

namespace Labstag\Tests\Integration;

use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ParamType;

class AdminControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    protected $urls = [
        'admin',
        'admin_param',
        'admin_profil',
        'admin_themes',
    ];

    protected $groupeDisable = [
        'visitor',
        'disable',
    ];

    /**
     * @dataProvider provideAllUrlWithoutParams
     * @param string $route
     */
    public function testUrl($route, $groupe)
    {
        $this->responseTest(
            $route,
            $groupe,
            !in_array($groupe, $this->groupeDisable)
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditParam(string $groupe)
    {
        $this->editTest(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_param',
            'getConfig'
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testPostParam($groupe)
    {
        $this->editPost(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_param',
            ParamType::class,
            'getConfig',
            false
        );
    }

    public function getConfig($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(Configuration::class);
        $data          = $repository->findAll();
        $config        = [];
        /** @var Configuration $row */
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        return $config;
    }
}
