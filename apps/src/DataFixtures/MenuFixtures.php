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
        $data = $this->installService->getData('menu');
        foreach ($data as $menu) {
            $this->addMenu($menu);
        }
    }

    protected function addMenu(
        array $dataMenu
    ): void
    {
        $menu = new Menu();
        $old  = clone $menu;
        if (array_key_exists('clef', $dataMenu)) {
            $menu->setClef($dataMenu['clef']);
            $this->addReference('menu_'.$dataMenu['clef'], $menu);
        }

        $this->menuRequestHandler->handle($old, $menu);
        if (!array_key_exists('childs', $dataMenu)) {
            return;
        }

        foreach ($dataMenu['childs'] as $child) {
            $this->addMenuChild($menu, $child);
        }
    }

    protected function addMenuChild(
        Menu $parent,
        array $child
    )
    {
        $menu = new Menu();
        $old  = clone $menu;
        $menu->setParent($parent);
        if (array_key_exists('separator', $child)) {
            $menu->setSeparateur(1);
        }

        if (array_key_exists('name', $child)) {
            $menu->setName($child['name']);
        }

        if (array_key_exists('data', $child)) {
            $menu->setData($child['data']);
        }

        $this->menuRequestHandler->handle($old, $menu);
        if (array_key_exists('childs', $child)) {
            foreach ($child['childs'] as $row) {
                $this->addMenuChild($menu, $row);
            }
        }
    }
}
