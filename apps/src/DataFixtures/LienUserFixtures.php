<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\LienUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class LienUserFixtures extends FixtureLib implements DependentFixtureInterface
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
        for ($index = 0; $index < self::NUMBER_LIEN; ++$index) {
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
        $lien = new LienUser();
        $old  = clone $lien;
        $lien->setRefUser($user);
        $lien->setName($faker->word());
        $lien->setAdresse($faker->url);
        $this->lienUserRH->handle($old, $lien);
    }
}
