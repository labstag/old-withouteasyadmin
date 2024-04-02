<?php

namespace Labstag\Domain;

use Labstag\Entity\Menu;

use Labstag\Form\Gestion\MenuType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\MenuSearch;

class MenuDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Menu::class;
    }

    public function getSearchData(): MenuSearch
    {
        return new MenuSearch();
    }

    public function getTemplates(): array
    {
        return [
            'index' => 'gestion/menu/index.html.twig',
            'trash' => 'gestion/menu/trash.html.twig',
            'move'  => 'gestion/menu/move.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_menu_index'   => $this->translator->trans('menu.title', [], 'gestion.breadcrumb'),
            'gestion_menu_add'     => $this->translator->trans('menu.add', [], 'gestion.breadcrumb'),
            'gestion_menu_divider' => $this->translator->trans('menu.divider', [], 'gestion.breadcrumb'),
            'gestion_menu_move'    => $this->translator->trans('menu.move', [], 'gestion.breadcrumb'),
            'gestion_menu_new'     => $this->translator->trans('menu.new', [], 'gestion.breadcrumb'),
            'gestion_menu_trash'   => $this->translator->trans('menu.trash', [], 'gestion.breadcrumb'),
            'gestion_menu_update'  => $this->translator->trans('menu.update', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return MenuType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_menu_edit',
            'empty'    => 'api_action_empty',
            'position' => 'gestion_menu_move',
            'list'     => 'gestion_menu_index',
            'new'      => 'gestion_menu_new',
            'restore'  => 'api_action_restore',
            'trash'    => 'gestion_menu_trash',
        ];
    }
}
