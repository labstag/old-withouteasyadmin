<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Libelle;
use Labstag\Lib\FixtureLib;

class LibelleFixtures extends FixtureLib implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    protected function add(ObjectManager $manager): void
    {
        unset($manager);
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER_LIBELLE; ++$index) {
            $this->addLibelle($faker, $index);
        }
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    protected function addLibelle(Generator $faker, int $index): void
    {
        $libelle    = new Libelle();
        $oldLibelle = clone $libelle;
        $libelle->setNom($faker->unique()->colorName);
        $this->addReference('libelle_'. $index, $libelle);
        $this->libelleRH->handle($oldLibelle, $libelle);
    }
}
