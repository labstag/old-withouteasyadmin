<?php

namespace Labstag\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;
use Labstag\Service\GuardRouteService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait MenuTrait
{

    protected FactoryInterface $factory;

    protected MenuRepository $repository;

    protected GuardRouteService $guardRouteService;

    protected TokenStorageInterface $token;

    public function __construct(
        FactoryInterface $factory,
        MenuRepository $repository,
        TokenStorageInterface $token,
        GuardRouteService $guardRouteService
    )
    {
        $this->token             = $token;
        $this->guardRouteService = $guardRouteService;
        $this->factory           = $factory;
        $this->repository        = $repository;
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

        $this->correctionMenu($menu);

        return $menu;
    }

    protected function correctionMenu(MenuItem $menu)
    {
        // dump(get_class_methods($menu));
        $data = $menu->getChildren();
        foreach ($data as $key => $row) {
            $extras = $row->getExtras();
            if (count($extras) != 0) {
                continue;
            }

            $children = $row->getChildren();
            if (count($children) == 0) {
                $menu->removeChild($key);
                continue;
            }

            $this->deleteParent($children, $key, $menu);
        }
    }

    protected function deleteParent($children, $key, $menu)
    {
        $divider = 0;
        foreach ($children as $child) {
            $extras = $child->getExtras();
            if (array_key_exists('divider', $extras) && $extras['divider'] == true) {
                $divider++;
            }
        }

        if ($divider == count($children)) {
            $menu->removeChild($key);
        }
    }

    protected function addMenu(MenuItem &$parent, Menu $child): void
    {
        $data      = [];
        $dataChild = $child->getData();
        if ($child->isSeparateur()) {
            $parent->addChild('')->setExtra('divider', true);
            return;
        }

        if (isset($dataChild['attr']['data-href'])) {
            $token = $this->token->getToken();
            $state = $this->guardRouteService->guardRoute($dataChild['attr']['data-href'], $token);
            if (!$state) {
                return;
            }

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
