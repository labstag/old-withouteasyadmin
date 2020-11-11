<?php

namespace Labstag\Tests\Integration;

use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    public function testSomething()
    {
        $this->assertTrue(true);
    }
}
