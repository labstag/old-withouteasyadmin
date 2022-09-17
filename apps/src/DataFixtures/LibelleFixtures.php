<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Libelle;
use Labstag\Lib\DataFixtureLib;

class LibelleFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $faker = $this->setFaker();
        for ($index = 0; $index < self::NUMBER_LIBELLE; ++$index) {
            $this->addLibelle($faker, $index);
        }
    }

    protected function addLibelle(Generator $generator, int $index): void
    {
        $libelle    = new Libelle();
        $oldLibelle = clone $libelle;
        $libelle->setName($generator->unique()->colorName());
        $this->addReference('libelle_'.$index, $libelle);
        $this->libelleRequestHandler->handle($oldLibelle, $libelle);
    }
}
