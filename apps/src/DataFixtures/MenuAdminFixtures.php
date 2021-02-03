<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Menu;
use Labstag\Lib\FixtureLib;

class MenuAdminFixtures extends FixtureLib implements DependentFixtureInterface
{

    protected ObjectManager $manager;

    protected function getMenuGeneral(): array
    {
        $data = [
            [
                'libelle' => 'Param',
                'data'    => [
                    'attr' => ['data-href' => 'admin_param'],
                ],
            ],
            [
                'libelle' => 'Configuration',
                'data'    => [
                    'attr' => ['data-href' => 'admin_configuration_index'],
                ],
            ],
            [
                'libelle' => 'Attachment',
                'data'    => [
                    'attr' => ['data-href' => 'admin_attachment_index'],
                ],
            ],
            [
                'libelle' => 'Geocode',
                'data'    => [
                    'attr' => ['data-href' => 'admin_geocode_index'],
                ],
            ],
            [
                'libelle' => 'Note Interne',
                'data'    => [
                    'attr' => ['data-href' => 'admin_noteinterne_index'],
                ],
            ],
            [
                'libelle' => 'Edito',
                'data'    => [
                    'attr' => ['data-href' => 'admin_edito_index'],
                ],
            ],
            [
                'libelle' => 'Template',
                'data'    => [
                    'attr' => ['data-href' => 'admin_template_index'],
                ],
            ],
            [
                'libelle' => 'Menu',
                'data'    => [
                    'attr' => ['data-href' => 'admin_menu_index'],
                ],
            ],
        ];

        return $data;
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    protected function getMenuUtilisateurs(): array
    {
        $data = [
            [
                'libelle' => 'Adresses',
                'data'    => [
                    'attr' => ['data-href' => 'admin_adresseuser_index'],
                ],
            ],
            [
                'libelle' => 'Emails',
                'data'    => [
                    'attr' => ['data-href' => 'admin_emailuser_index'],
                ],
            ],
            [
                'libelle' => 'Liens',
                'data'    => [
                    'attr' => ['data-href' => 'admin_lienuser_index'],
                ],
            ],
            [
                'libelle' => 'Phones',
                'data'    => [
                    'attr' => ['data-href' => 'admin_phoneuser_index'],
                ],
            ],
            [
                'libelle' => 'Groupes',
                'data'    => [
                    'attr' => ['data-href' => 'admin_groupuser_index'],
                ],
            ],
            ['separator' => 1],
            [
                'libelle' => 'Droits',
                'data'    => [
                    'attr' => ['data-href' => 'admin_guard_index'],
                ],
            ],
            [
                'libelle' => 'Liste',
                'data'    => [
                    'attr' => ['data-href' => 'admin_user_index'],
                ],
            ],
        ];

        return $data;
    }

    protected function getMenuAdmin(): array
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
    protected function getMenuProfilAdmin(): array
    {
        $data = [
            [
                'libelle' => 'Mon profil',
                'data'    => [
                    'attr' => ['data-href' => 'admin_profil'],
                ],
            ],
            [
                'libelle' => 'Logout',
                'data'    => [
                    'attr' => ['data-href' => 'app_logout'],
                ],
            ],
        ];

        return $data;
    }

    protected function getMenuAdminProfilAdmin(): array
    {
        $data = [
            [
                'libelle' => 'Mon compte',
                'childs'  => $this->getMenuProfilAdmin(),
            ],
        ];

        return $data;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $menus = [
            'admin'        => $this->getMenuAdmin(),
            'admin-profil' => $this->getMenuAdminProfilAdmin(),
            'public'       => [],
        ];

        $index = 0;
        foreach ($menus as $key => $child) {
            $this->saveMenu($index, $key, $child);
            $index++;
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
            $indexChild++;
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
                $indexChild++;
            }
        }
    }
}
