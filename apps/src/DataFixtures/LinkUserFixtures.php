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
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $this->loadForeachUser(self::NUMBER_LINK, 'addLink');
    }

    protected function addLink(
        Generator $generator,
        User $user
    ): void
    {
        $linkUser = new LinkUser();
        $old  = clone $linkUser;
        $linkUser->setRefUser($user);
        $linkUser->setName($generator->word());
        $linkUser->setAddress($generator->url());

        $this->linkUserRequestHandler->handle($old, $linkUser);
    }
}
