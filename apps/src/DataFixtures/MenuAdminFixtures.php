<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Menu;
use Labstag\Lib\FixtureLib;

class MenuAdminFixtures extends FixtureLib implements DependentFixtureInterface
{

    protected ObjectManager $manager;

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $menuadmin       = $this->installService->getData('menuadmin');
        $menuadminprofil = $this->installService->getData('menuadminprofil');

        $menus = [
            'admin'        => $menuadmin,
            'admin-profil' => $menuadminprofil,
            'public'       => [],
        ];

        $index = 0;
        foreach ($menus as $key => $child) {
            $this->saveMenu($index, $key, $child);
            ++$index;
        }

        $manager->flush();
    }

    protected function saveMenu(int $index, string $key, array $childs): void
    {
        $menu = new Menu();
        $menu->setPosition($index);
        $menu->setClef($key);
        $this->manager->persist($menu);
        $indexChild = 0;
        foreach ($childs as $attr) {
            $this->addChild($indexChild, $menu, $attr);
            ++$indexChild;
        }
    }

    protected function addChild(int $index, Menu $menu, array $attr): void
    {
        $child = new Menu();
        $child->setPosition($index);
        $child->setParent($menu);
        if (isset($attr['separator'])) {
            $child->setSeparateur(true);
            $this->manager->persist($child);

            return;
        }

        $child->setLibelle($attr['libelle']);
        if (isset($attr['data'])) {
            $child->setData($attr['data']);
        }

        $this->manager->persist($child);
        if (isset($attr['childs'])) {
            $indexChild = 0;
            foreach ($attr['childs'] as $attrChild) {
                $this->addChild($indexChild, $child, $attrChild);
                ++$indexChild;
            }
        }
    }
}
