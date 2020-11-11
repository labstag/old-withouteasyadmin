<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Groupe;


/**
 * @codeCoverageIgnore
 */
class GroupFixtures extends Fixture
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
        foreach ($groupes as $row) {
            $this->addGroupe($manager, $row);
        }

        $manager->flush();
    }

    private function addGroupe(ObjectManager $manager, string $row): void
    {
        $groupe = new Groupe();
        $groupe->setName($row);
        $manager->persist($groupe);
    }
}
