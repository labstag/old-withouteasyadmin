<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Groupe;

class GroupFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $groupes = [
            'visiteur',
            'admin',
            'user',
        ];
        foreach ($groupes as $row) {
            $groupe = new Groupe();
            $groupe->setName($row);
            $manager->persist($groupe);
        }

        $manager->flush();
    }
}
