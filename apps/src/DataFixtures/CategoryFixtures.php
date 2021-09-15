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
        $data = [];
        for ($index = 0; $index < self::NUMBER_CATEGORY; ++$index) {
            $data[] = $this->addCategory($faker, $index, $data);
        }
    }

    protected function addCategory(Generator $faker, int $index, array $data = []): Category
    {
        $category    = new Category();
        $oldCategory = clone $category;
        $category->setName($faker->unique()->colorName);
        if (0 != count($data) && 1 == rand(0, 1)) {
            $dateId = array_rand($data);
            $category->setParent($data[$dateId]);
        }
        $this->addReference('Category_'.$index, $category);
        $this->categoryRH->handle($oldCategory, $category);

        return $category;
    }
}
