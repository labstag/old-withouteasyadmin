<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Category;
use Labstag\Lib\FixtureLib;

class CategoryFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    protected function add(ObjectManager $manager): void
    {
        unset($manager);
        $faker = $this->setFaker();
        for ($index = 0; $index < self::NUMBER_CATEGORY; ++$index) {
            $this->addCategory($faker, $index);
        }
    }

    protected function addCategory(Generator $faker, int $index): Category
    {
        $category    = new Category();
        $oldCategory = clone $category;
        $category->setName($faker->unique()->colorName);
        $indexCategory = $faker->numberBetween(0, $index);
        $code          = 'category_'.$indexCategory;
        if ($this->hasReference($code) && 1 == random_int(0, 1)) {
            $parent = $this->getReference($code);
            $category->setParent($parent);
        }

        $this->addReference('category_'.$index, $category);
        $this->categoryRH->handle($oldCategory, $category);

        return $category;
    }
}
