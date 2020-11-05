<?php

namespace Labstag\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminMenuBuilder
{

    private FactoryInterface $factory;

    private MenuRepository $repository;

    public function __construct(
        FactoryInterface $factory,
        MenuRepository $repository
    )
    {
        $this->factory    = $factory;
        $this->repository = $repository;
    }

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        unset($requestStack);
        $menu = $this->factory->createItem('menulabstag');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $data      = $this->repository->findOneBy(
            [
                'clef'   => 'admin',
                'parent' => null,
            ]
        );
        if (!($data instanceof Menu)) {
            return $menu;
        }

        $childrens = $data->getChildren();
        foreach ($childrens as $child) {
            $this->addMenu($menu, $child);
        }

        return $menu;
    }

    private function addMenu(MenuItem $parent, Menu $child)
    {
        $data      = [];
        $dataChild = $child->getData();
        if (isset($dataChild['attr']['data-href'])) {
            $data['route'] = $dataChild['attr']['data-href'];
        }

        if (isset($dataChild['attr']['data-href-params'])) {
            $data['routeParameters'] = $dataChild['attr']['data-href-params'];
        }

        $menu      = $parent->addChild(
            $child->getLibelle(),
            $data
        );
        $childrens = $child->getChildren();
        foreach ($childrens as $child) {
            $this->addMenu($menu, $child);
        }
    }
}
