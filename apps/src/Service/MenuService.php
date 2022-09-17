<?php

namespace Labstag\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuService
{
    public function __construct(
        protected FactoryInterface $menuFactory,
        protected MenuRepository $menuRepository,
        protected TokenStorageInterface $tokenStorage,
        protected GuardService $guardService,
    )
    {
    }

    public function createMenu(Menu $menu): ItemInterface
    {
        $clef      = $menu->getClef();
        $menuItem = $this->menuFactory->createItem('menulabstag');
        $menuItem->setChildrenAttribute('class', 'navbar-nav menu-'.$clef);

        return $this->setData($menuItem, $clef);
    }

    public function createMenus()
    {
        $menus = [];
        $all   = $this->menuRepository->findAllCode();
        foreach ($all as $row) {
            $key         = $row->getClef();
            $menus[$key] = $this->createMenu($row);
        }

        return $menus;
    }

    public function setData(ItemInterface $item, string $clef): ItemInterface
    {
        $data = $this->menuRepository->findOneBy(
            [
                'clef'   => $clef,
                'parent' => null,
            ]
        );

        if (!$data instanceof Menu) {
            return $item;
        }

        $childrens = $data->getChildren();
        foreach ($childrens as $children) {
            $this->addMenu($item, $children);
        }

        $this->correctionMenu($item);

        return $item;
    }

    protected function addMenu(MenuItem &$menuItem, Menu $child): void
    {
        $data      = [];
        $dataChild = $child->getData();
        if ($child->isSeparateur()) {
            $menuItem->addChild('')->setExtra('divider', true);

            return;
        }

        if (isset($dataChild['target']) && empty($dataChild['target'])) {
            unset($dataChild['target']);
        }

        if (isset($dataChild['route'])) {
            $token = $this->tokenStorage->getToken();
            $state = $this->guardService->guardRoute($dataChild['route'], $token);
            if (!$state) {
                return;
            }

            $data['route'] = $dataChild['route'];
            unset($dataChild['route']);
        }

        $this->setDataChild($dataChild, $data);

        $item      = $menuItem->addChild(
            $child->getName(),
            $data
        );
        $childrens = $child->getChildren();
        foreach ($childrens as $children) {
            $this->addMenu($item, $children);
        }
    }

    protected function correctionMenu(MenuItem $menuItem)
    {
        $data = $menuItem->getChildren();
        foreach ($data as $key => $row) {
            $extras = $row->getExtras();
            if (0 != count($extras) || '' != $row->getUri()) {
                continue;
            }

            $children = $row->getChildren();
            if (0 == count($children)) {
                $menuItem->removeChild($key);

                continue;
            }

            $this->deleteParent($children, $key, $menuItem);
        }
    }

    protected function deleteParent($children, $key, $menu)
    {
        $divider = 0;
        foreach ($children as $child) {
            $extras = $child->getExtras();
            if (array_key_exists('divider', $extras) && true == $extras['divider']) {
                ++$divider;
            }
        }

        if ($divider == (is_countable($children) ? count($children) : 0)) {
            $menu->removeChild($key);
        }
    }

    private function setDataChild(&$dataChild, &$data)
    {
        if (isset($dataChild['url'])) {
            $data['uri'] = $dataChild['url'];
            unset($dataChild['url']);
        }

        if (isset($dataChild['params'])) {
            $data['routeParameters'] = $dataChild['params'];
            unset($dataChild['params']);
        }

        if (0 != count((array) $dataChild)) {
            $data['linkAttributes'] = $dataChild;
        }
    }
}
