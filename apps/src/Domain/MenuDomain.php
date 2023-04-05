<?php

namespace Labstag\Domain;

use Labstag\Entity\Menu;

use Labstag\Form\Admin\MenuType;
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
            'index' => 'admin/menu/index.html.twig',
            'trash' => 'admin/menu/trash.html.twig',
            'move'  => 'admin/menu/move.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'admin_menu_index'   => $this->translator->trans('menu.title', [], 'admin.breadcrumb'),
            'admin_menu_add'     => $this->translator->trans('menu.add', [], 'admin.breadcrumb'),
            'admin_menu_divider' => $this->translator->trans('menu.divider', [], 'admin.breadcrumb'),
            'admin_menu_move'    => $this->translator->trans('menu.move', [], 'admin.breadcrumb'),
            'admin_menu_new'     => $this->translator->trans('menu.new', [], 'admin.breadcrumb'),
            'admin_menu_trash'   => $this->translator->trans('menu.trash', [], 'admin.breadcrumb'),
            'admin_menu_update'  => $this->translator->trans('menu.update', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_menu_edit',
            'empty'    => 'api_action_empty',
            'position' => 'admin_menu_move',
            'list'     => 'admin_menu_index',
            'new'      => 'admin_menu_new',
            'restore'  => 'api_action_restore',
            'trash'    => 'admin_menu_trash',
        ];
    }
}
