<?php

namespace Labstag\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;

trait MenuTrait
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

    public function setData(ItemInterface $menu, string $clef): ItemInterface
    {
        $data = $this->repository->findOneBy(
            [
                'clef'   => $clef,
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

    private function addMenu(MenuItem &$parent, Menu $child): void
    {
        $data      = [];
        $dataChild = $child->getData();
        if ($child->isSeparateur()) {
            $parent->addChild('')->setExtra('divider', true);
            return;
        }

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
