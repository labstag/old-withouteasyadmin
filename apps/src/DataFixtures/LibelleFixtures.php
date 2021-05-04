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
    const NUMBER = 10;

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    protected function add(ObjectManager $manager): void
    {
        unset($manager);
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addLibelle($faker);
        }
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    protected function addLibelle(Generator $faker): void
    {
        $libelle    = new Libelle();
        $oldLibelle = clone $libelle;
        $libelle->setNom($faker->unique()->colorName);
        $this->templateRH->handle($oldLibelle, $libelle);
    }
}
