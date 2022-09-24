<?php

namespace Labstag\Domain;

use Labstag\Entity\Menu;

use Labstag\Lib\DomainLib;
use Labstag\Repository\MenuRepository;
use Labstag\RequestHandler\MenuRequestHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuDomain extends DomainLib
{
    public function __construct(
        protected MenuRequestHandler $menuRequestHandler,
        protected MenuRepository $menuRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Menu::class;
    }

    public function getRepository()
    {
        return $this->menuRepository;
    }

    public function getRequestHandler()
    {
        return $this->menuRequestHandler;
    }

    /**
     * @return mixed[]
     */
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

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_menu_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_menu_index',
            'new'     => 'admin_menu_new',
            'restore' => 'api_action_restore',
            'trash'   => 'admin_menu_trash',
        ];
    }
}
