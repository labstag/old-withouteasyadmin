<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Configuration;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\Dotenv\Dotenv;
use Labstag\Service\OauthService;
use Psr\EventDispatcher\EventDispatcherInterface;

class RouterFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $all = $this->guardService->all();
        foreach (array_keys($all) as $name) {
            $this->guardService->save($name);
        }
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }
}
