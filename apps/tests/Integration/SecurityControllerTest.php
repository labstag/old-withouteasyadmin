<?php

namespace Labstag\Tests\Integration;

use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    protected $urls = [
        'app_lost',
        'app_login',
        'disclaimer',
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
            true
        );
    }
}
