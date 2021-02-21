<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Lib\FixtureLib;

class GroupFixtures extends FixtureLib implements DependentFixtureInterface
{
    protected function getGroupes(): array
    {
        $data = [];
        $file = __DIR__.'/../../json/group.json';
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        return $data;
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $groupes = $this->getGroupes();
        foreach ($groupes as $key => $row) {
            $this->addGroupe($key, $row);
        }
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }
}
