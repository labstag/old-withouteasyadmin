<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Menu;
use Labstag\Lib\FixtureLib;

class MenuFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $generator = $this->setFaker();
        $data      = $this->installService->getData('menu');
        foreach ($data as $menu) {
            $this->addMenu($generator, $menu, $objectManager);
        }

        $objectManager->flush();
    }

    protected function addMenu(
        Generator $generator,
        array $dataMenu,
        ObjectManager $objectManager
    ): void {
        $menu = new Menu();
        if (array_key_exists('clef', $dataMenu)) {
            $this->addReference('menu_'.$dataMenu['clef'], $menu);
            $menu->setClef($dataMenu['clef']);
        }

        $objectManager->persist($menu);
        if (!array_key_exists('childs', $dataMenu)) {
            return;
        }

        foreach ($dataMenu['childs'] as $position => $child) {
            $this->addMenuChild($generator, $position, $menu, $child, $objectManager);
        }
    }

    protected function addMenuChild(
        Generator $generator,
        int $position,
        Menu $parent,
        array $child,
        ObjectManager $objectManager
    ): void {
        $menu = new Menu();
        $menu->setPosition($position + 1);
        $menu->setParent($parent);
        $menu->setSeparateur(array_key_exists('separator', $child));
        if (array_key_exists('name', $child)) {
            $menu->setName($child['name']);
        }

        if (array_key_exists('data', $child)) {
            $menu->setData($child['data']);
        }

        $objectManager->persist($menu);
        if (array_key_exists('childs', $child)) {
            foreach ($child['childs'] as $i => $row) {
                $this->addMenuChild($generator, $i, $menu, $row, $objectManager);
            }
        }
    }
}
