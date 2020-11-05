<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Menu;

class MenuAdminFixtures extends Fixture
{

    private ObjectManager $manager;

    private function getMenuGeneral(): array
    {
        $data = [
            [
                'libelle' => 'Configuration',
                'data'    => [
                    'attr' => ['data-href' => 'configuration_index'],
                ],
            ],
            [
                'libelle' => 'Note Interne',
                'data'    => [
                    'attr' => ['data-href' => 'note_interne_index'],
                ],
            ],
            [
                'libelle' => 'Edito',
                'data'    => [
                    'attr' => ['data-href' => 'edito_index'],
                ],
            ],
            [
                'libelle' => 'Template',
                'data'    => [
                    'attr' => ['data-href' => 'template_index'],
                ],
            ],
            [
                'libelle' => 'Menu',
                'data'    => [
                    'attr' => ['data-href' => 'menu_index'],
                ],
            ],
        ];

        return $data;
    }

    private function getMenuUtilisateurs()
    {
        $data = [
            [
                'libelle' => 'Adresses',
                'data'    => [
                    'attr' => ['data-href' => 'adresse_user_index'],
                ],
            ],
            [
                'libelle' => 'Emails',
                'data'    => [
                    'attr' => ['data-href' => 'email_user_index'],
                ],
            ],
            [
                'libelle' => 'Liens',
                'data'    => [
                    'attr' => ['data-href' => 'lien_user_index'],
                ],
            ],
            [
                'libelle' => 'Phones',
                'data'    => [
                    'attr' => ['data-href' => 'phone_user_index'],
                ],
            ],
            [
                'libelle' => 'Groupes',
                'data'    => [
                    'attr' => ['data-href' => 'groupe_index'],
                ],
            ],
            [
                'libelle' => 'Liste',
                'data'    => [
                    'attr' => ['data-href' => 'user_index'],
                ],
            ],
        ];

        return $data;
    }

    private function getMenuAdmin(): array
    {
        $data = [
            [
                'libelle' => 'Home',
                'data'    => [
                    'attr' => ['data-href' => 'admin'],
                ],
            ],
            [
                'libelle' => 'Général',
                'childs'  => $this->getMenuGeneral(),
            ],
            [
                'libelle' => 'Utilisateurs',
                'childs'  => $this->getMenuUtilisateurs(),
            ],
            [
                'libelle' => 'Etablissements',
                'childs'  => [],
            ],
            [
                'libelle' => 'Histoires',
                'childs'  => [],
            ],
            [
                'libelle' => 'Bookmarks',
                'childs'  => [],
            ],
            [
                'libelle' => 'Partenaires',
                'childs'  => [],
            ],
            [
                'libelle' => 'Posts',
                'childs'  => [],
            ],
        ];

        return $data;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $menus = [
            'admin'  => $this->getMenuAdmin(),
            'public' => [],
        ];

        $index = 0;
        foreach ($menus as $key => $child) {
            $this->saveMenu($index, $key, $child);
            $index++;
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function saveMenu(int $index, string $key, array $childs): void
    {
        $menu = new Menu();
        $menu->setPosition($index);
        $menu->setClef($key);
        $this->manager->persist($menu);
        $indexChild = 0;
        foreach ($childs as $attr) {
            $this->addChild($indexChild, $menu, $attr);
            $indexChild++;
        }
    }

    private function addChild(int $index, Menu $menu, array $attr): void
    {
        $child = new Menu();
        $child->setPosition($index);
        $child->setLibelle($attr['libelle']);
        if (isset($attr['data'])) {
            $child->setData($attr['data']);
        }

        $child->setParent($menu);
        $this->manager->persist($child);
        if (isset($attr['childs'])) {
            $indexChild = 0;
            foreach ($attr['childs'] as $attrChild) {
                $this->addChild($indexChild, $child, $attrChild);
                $indexChild++;
            }
        }
    }
}
