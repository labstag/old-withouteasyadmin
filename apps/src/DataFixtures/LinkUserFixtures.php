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
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker = $this->setFaker();
        $users = $this->installService->getData('user');
        for ($index = 0; $index < self::NUMBER_LINK; ++$index) {
            $indexUser = $faker->numberBetween(0, count($users) - 1);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addLink($faker, $user);
        }
    }

    protected function addLink(
        Generator $faker,
        User $user
    ): void
    {
        $link = new LinkUser();
        $old  = clone $link;
        $link->setRefUser($user);
        $link->setName($faker->word());
        $link->setAddress($faker->url);
        $this->linkUserRH->handle($old, $link);
    }
}
