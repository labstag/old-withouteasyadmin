<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\LinkUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class LinkUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $this->loadForeachUser(self::NUMBER_LINK, 'addLink', $objectManager);
    }

    protected function addLink(
        Generator $generator,
        User $user,
        ObjectManager $objectManager
    ): void {
        $linkUser = new LinkUser();
        $linkUser->setRefUser($user);
        $linkUser->setName($generator->word());
        $linkUser->setAddress($generator->url());

        $objectManager->persist($linkUser);
    }
}
