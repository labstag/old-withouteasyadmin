<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Lib\FixtureLib;

class GroupFixtures extends FixtureLib implements DependentFixtureInterface
{
    private function getGroupes(): array
    {
        return [
            'visiteur',
            'admin',
            'superadmin',
            'user',
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $groupes = $this->getGroupes();
        foreach ($groupes as $key => $row) {
            $this->addGroupe($manager, $key, $row);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CacheFixtures::class];
    }
}
