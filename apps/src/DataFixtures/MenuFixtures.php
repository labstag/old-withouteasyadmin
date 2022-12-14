<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
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
        $faker = $this->setFaker();
        $data  = $this->installService->getData('menu');
        foreach ($data as $menu) {
            $this->addMenu($faker, $menu);
        }
    }

    protected function addMenu(
        $faker,
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
            $this->addMenuChild($faker, $position, $menu, $child);
        }
    }

    protected function addMenuChild(
        $faker,
        int $position,
        Menu $parent,
        array $child
    )
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
                $this->addMenuChild($faker, $i, $menu, $row);
            }
        }
    }
}
