<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Groupe;
use Labstag\Lib\DataFixtureLib;

class GroupFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $groupes = $this->installService->getData('group');
        foreach ($groupes as $key => $row) {
            $this->addGroupe($key, $row);
        }
    }

    protected function addGroupe(
        int $key,
        string $row
    ): void {
        $groupe = new Groupe();
        $old    = clone $groupe;
        $groupe->setCode($row);
        $groupe->setName($row);
        $this->addReference('groupe_'.$key, $groupe);
        $this->groupeRequestHandler->handle($old, $groupe);
    }
}
