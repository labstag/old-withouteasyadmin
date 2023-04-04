<?php

namespace Labstag\Domain\User;

use Labstag\Entity\LinkUser;

use Labstag\Form\Admin\Search\User\LinkUserType as SearchLinkUserType;
use Labstag\Form\Admin\User\LinkUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\User\LinkUserSearch;

class LinkUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return LinkUser::class;
    }

    public function getSearchData(): LinkUserSearch
    {
        return new LinkUserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchLinkUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'admin/user/link/index.html.twig',
            'trash'   => 'admin/user/link/index.html.twig',
            'show'    => 'admin/user/link/show.html.twig',
            'preview' => 'admin/user/link/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'admin_linkuser_index'   => $this->translator->trans('linkuser.title', [], 'admin.breadcrumb'),
            'admin_linkuser_edit'    => $this->translator->trans('linkuser.edit', [], 'admin.breadcrumb'),
            'admin_linkuser_new'     => $this->translator->trans('linkuser.new', [], 'admin.breadcrumb'),
            'admin_linkuser_trash'   => $this->translator->trans('linkuser.trash', [], 'admin.breadcrumb'),
            'admin_linkuser_preview' => $this->translator->trans('linkuser.preview', [], 'admin.breadcrumb'),
            'admin_linkuser_show'    => $this->translator->trans('linkuser.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return LinkUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_linkuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_linkuser_index',
            'new'     => 'admin_linkuser_new',
            'preview' => 'admin_linkuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_linkuser_show',
            'trash'   => 'admin_linkuser_trash',
        ];
    }
}
