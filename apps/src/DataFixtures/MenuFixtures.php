<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Menu;
use Labstag\Lib\FixtureLib;

class MenuFixtures extends FixtureLib implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $generator = $this->setFaker();
        $data      = $this->installService->getData('menu');
        foreach ($data as $menu) {
            $this->addMenu($generator, $menu);
        }
    }

    protected function addMenu(
        Generator $generator,
        array $dataMenu
    ): void
    {
        $menu = new Menu();
        $old  = clone $menu;
        if (array_key_exists('clef', $dataMenu)) {
            $this->addReference('menu_'.$dataMenu['clef'], $menu);
            $menu->setClef($dataMenu['clef']);
        }

        $this->menuRequestHandler->handle($old, $menu);
        if (!array_key_exists('childs', $dataMenu)) {
            return;
        }

        foreach ($dataMenu['childs'] as $position => $child) {
            $this->addMenuChild($generator, $position, $menu, $child);
        }
    }

    protected function addMenuChild(
        Generator $generator,
        int $position,
        Menu $parent,
        array $child
    ): void
    {
        $menu = new Menu();
        $old  = clone $menu;
        $menu->setPosition($position + 1);
        $menu->setParent($parent);
        $menu->setSeparateur(array_key_exists('separator', $child));
        if (array_key_exists('name', $child)) {
            $menu->setName($child['name']);
        }

        if (array_key_exists('data', $child)) {
            $menu->setData([$child['data']]);
        }

        $this->menuRequestHandler->handle($old, $menu);
        if (array_key_exists('childs', $child)) {
            foreach ($child['childs'] as $i => $row) {
                $this->addMenuChild($generator, $i, $menu, $row);
            }
        }
    }
}
