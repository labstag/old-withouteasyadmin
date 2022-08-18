<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Libelle;
use Labstag\Lib\DataFixtureLib;

class LibelleFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker = $this->setFaker();
        for ($index = 0; $index < self::NUMBER_LIBELLE; ++$index) {
            $this->addLibelle($faker, $index);
        }
    }

    protected function addLibelle(Generator $faker, int $index): void
    {
        $libelle    = new Libelle();
        $oldLibelle = clone $libelle;
        $libelle->setName($faker->unique()->colorName());
        $this->addReference('libelle_'.$index, $libelle);
        $this->libelleRH->handle($oldLibelle, $libelle);
    }
}
