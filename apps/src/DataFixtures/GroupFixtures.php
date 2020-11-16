<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Groupe;
use Labstag\Lib\FixtureLib;

/**
 * @codeCoverageIgnore
 */
class GroupFixtures extends FixtureLib
{
    private function getGroupes(): array
    {
        $groupes = [
            'visiteur',
            'admin',
            'superadmin',
            'user',
        ];
        return $groupes;
    }

    public function load(ObjectManager $manager)
    {
        $groupes = $this->getGroupes();
        foreach ($groupes as $key => $row) {
            $this->addGroupe($manager, $key, $row);
        }

        $manager->flush();
    }
}
